<?php
// app/Livewire/Rehab/Cashier/RehabPaymentProcess.php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabPaymentInstallment;
use App\Models\RehabEncounter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RehabPaymentProcess extends Component
{
    public RehabOrder $rehabOrder;
    public $paymentMethod = 'cash';
    public $paymentAmount;
    public $changeAmount = 0;
    public $showConfirmModal = false;

    // Installment properties
    public $paymentInstallments = [];
    public $selectedInstallment = null;
    public $showPaymentPlanModal = false;
    public $paymentType = 'full';
    public $installmentCount = 2;
    public $customInstallments = [];
    public $totalDurationDays = 0;
    public $bedCost = 0;
    public $grandTotal = 0;

    public function mount($rehabOrder)
    {
        $this->rehabOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'encounter.encounter.doctor',
            'packages.items',
            'bedSelections' => function ($query) {
                $query->with(['bedClass', 'bed'])->latest();
            },
            'paymentInstallments' => function ($query) {
                $query->orderBy('installment_number');
            }
        ])->findOrFail($rehabOrder);

        $this->calculateGrandTotal();
        $this->loadInstallments();
    }

    public function calculateGrandTotal()
    {
        $this->grandTotal = $this->rehabOrder->total_amount;

        if ($this->rehabOrder->bedSelections->isNotEmpty()) {
            $bedSelection = $this->rehabOrder->bedSelections->first();
            $this->bedCost = $bedSelection->total_price ?? 0;
            $this->grandTotal += $this->bedCost;
            $this->totalDurationDays = $bedSelection->duration_days ?? 0;
        }
    }

    public function loadInstallments()
    {
        $this->paymentInstallments = $this->rehabOrder->paymentInstallments()
            ->orderBy('installment_number')
            ->get();

        // If no installments exist, show payment plan modal
        if ($this->paymentInstallments->isEmpty()) {
            $this->showPaymentPlanModal = true;
            $this->initializeCustomInstallments();
        }
    }

    public function initializeCustomInstallments()
    {
        $this->customInstallments = [];

        for ($i = 1; $i <= $this->installmentCount; $i++) {
            $amount = $this->grandTotal / $this->installmentCount;

            $dueDate = now();
            if ($this->totalDurationDays > 0) {
                $daysPerInstallment = floor($this->totalDurationDays / $this->installmentCount);
                $dueDate = now()->addDays($daysPerInstallment * $i);
            }

            $this->customInstallments[] = [
                'number' => $i,
                'amount' => round($amount, 2),
                'due_date' => $dueDate->format('Y-m-d'),
                'percentage' => round(100 / $this->installmentCount, 2)
            ];
        }

        // Adjust last installment
        $total = array_sum(array_column($this->customInstallments, 'amount'));
        if (abs($total - $this->grandTotal) > 0.01) {
            $lastIndex = count($this->customInstallments) - 1;
            $this->customInstallments[$lastIndex]['amount'] = round(
                $this->customInstallments[$lastIndex]['amount'] + ($this->grandTotal - $total),
                2
            );
        }
    }

    public function createInstallmentSchedule()
    {
        try {
            DB::transaction(function () {
                // Delete any existing installments
                RehabPaymentInstallment::where('rehab_order_id', $this->rehabOrder->id)->delete();

                if ($this->paymentType === 'installment') {
                    foreach ($this->customInstallments as $installment) {
                        RehabPaymentInstallment::create([
                            'rehab_order_id' => $this->rehabOrder->id,
                            'installment_number' => $installment['number'],
                            'amount' => $installment['amount'],
                            'due_date' => $installment['due_date'],
                            'status' => 'pending',
                            'created_by' => auth()->id()
                        ]);
                    }

                    $this->rehabOrder->update([
                        'payment_type' => 'installment',
                        'installment_count' => $this->installmentCount,
                        'payment_status' => 'pending'
                    ]);
                } else {
                    RehabPaymentInstallment::create([
                        'rehab_order_id' => $this->rehabOrder->id,
                        'installment_number' => 1,
                        'amount' => $this->grandTotal,
                        'due_date' => now(),
                        'status' => 'pending',
                        'created_by' => auth()->id()
                    ]);

                    $this->rehabOrder->update([
                        'payment_type' => 'full',
                        'installment_count' => 1,
                        'payment_status' => 'pending'
                    ]);
                }
            });

            $this->showPaymentPlanModal = false;
            $this->loadInstallments();
            $this->dispatch('notify', [
                'message' => 'Payment plan created successfully',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Error creating payment plan: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function selectInstallment($installmentId)
    {
        $this->selectedInstallment = RehabPaymentInstallment::find($installmentId);
        if ($this->selectedInstallment) {
            $this->paymentAmount = $this->selectedInstallment->getRemainingAmount();
            $this->changeAmount = 0;
        }
    }

    public function updatedPaymentAmount()
    {
        if ($this->selectedInstallment) {
            $remainingAmount = $this->selectedInstallment->getRemainingAmount();
            if ($this->paymentAmount > $remainingAmount) {
                $this->paymentAmount = $remainingAmount;
            }

            if ($this->paymentAmount >= $remainingAmount) {
                $this->changeAmount = $this->paymentAmount - $remainingAmount;
            } else {
                $this->changeAmount = 0;
            }
        }
    }

    public function processPayment()
    {
        $this->validate([
            'paymentMethod' => 'required|in:cash,card,insurance,bank_transfer',
            'selectedInstallment' => 'required',
            'paymentAmount' => 'required|numeric|min:1',
        ]);

        $this->showConfirmModal = true;
    }

public function confirmPayment()
{
    try {
        DB::transaction(function () {
            $remainingAmount = $this->selectedInstallment->getRemainingAmount();

            if ($this->paymentAmount > $remainingAmount) {
                throw new \Exception('Payment amount exceeds remaining balance');
            }

            // Get the rehab encounter
            $rehabEncounter = $this->rehabOrder->encounter;
            \Log::info('=== PAYMENT PROCESSING ===');
            \Log::info('Installment number: ' . $this->selectedInstallment->installment_number);
            \Log::info('Current encounter status: ' . ($rehabEncounter->status ?? 'null'));
            \Log::info('Payment amount: ' . $this->paymentAmount);

            // Update installment
            $newPaidAmount = $this->selectedInstallment->paid_amount + $this->paymentAmount;
            $installmentStatus = $newPaidAmount >= $this->selectedInstallment->amount ? 'paid' : 'partial';

            $this->selectedInstallment->update([
                'paid_amount' => $newPaidAmount,
                'paid_date' => $installmentStatus === 'paid' ? now() : null,
                'status' => $installmentStatus,
                'updated_by' => auth()->id()
            ]);

            \Log::info('Installment updated to: ' . $installmentStatus);

            // Update order paid amount
            $totalPaid = $this->rehabOrder->paymentInstallments()->sum('paid_amount');
            $this->rehabOrder->paid_amount = $totalPaid;

            // Check if all installments are paid
            $pendingInstallments = $this->rehabOrder->paymentInstallments()
                ->where('status', '!=', 'paid')
                ->count();

            \Log::info('Pending installments left: ' . $pendingInstallments);

            // FIXED: Get treatment status from encounter to preserve it
            $encounterStatus = $rehabEncounter ? $rehabEncounter->status : null;
            $isTreatmentStarted = in_array($encounterStatus, ['treatment_in_progress', 'completed']);

            if ($pendingInstallments === 0) {
                // THIS IS THE LAST PAYMENT
                \Log::info('Processing LAST payment');

                $this->rehabOrder->status = 'paid';
                $this->rehabOrder->payment_status = 'paid';
                $this->rehabOrder->payment_method = $this->paymentMethod;
                $this->rehabOrder->paid_at = now();
                $this->rehabOrder->save();

                \Log::info('RehabOrder updated to paid');

                // CRITICAL FIX: Only update rehab encounter if treatment hasn't started
                if ($rehabEncounter) {
                    if ($isTreatmentStarted) {
                        // Treatment already in progress or completed - DO NOT CHANGE
                        \Log::info('Treatment already started/completed, preserving status: ' . $encounterStatus);
                        // No update needed - keep existing status
                    } else {
                        // Treatment not started yet - send to rehab
                        \Log::info('Treatment not started, sending to rehab');
                        $rehabEncounter->update(['status' => 'sent_to_rehab']);
                    }
                }

                session()->flash('message', 'Full payment completed!');

            } else {
                // NOT LAST PAYMENT
                $isFirstInstallment = $this->selectedInstallment->installment_number === 1;
                \Log::info('Processing NON-last payment, is first: ' . ($isFirstInstallment ? 'yes' : 'no'));

                if ($isFirstInstallment && $installmentStatus === 'paid') {
                    // FIRST INSTALLMENT FULLY PAID - ONLY send to treatment if not already started
                    $this->rehabOrder->payment_status = 'partial';
                    $this->rehabOrder->status = 'sent_to_cashier';
                    $this->rehabOrder->payment_method = $this->paymentMethod;

                    // Only update rehab encounter if treatment hasn't started
                    if ($rehabEncounter && !$isTreatmentStarted) {
                        \Log::info('First installment paid, sending to rehab');
                        $rehabEncounter->update(['status' => 'sent_to_rehab']);
                    } else {
                        \Log::info('First installment paid but treatment already ' . $encounterStatus);
                    }

                    $this->rehabOrder->save();
                    session()->flash('message', 'First installment paid!');
                } else {
                    // Partial payment or subsequent installments
                    $this->rehabOrder->payment_status = 'partial';
                    $this->rehabOrder->status = 'sent_to_cashier';
                    $this->rehabOrder->save();

                    // DO NOT change rehab encounter status - keep current (treatment_in_progress or completed)
                    \Log::info('Partial payment - preserving encounter status: ' . $encounterStatus);
                    session()->flash('message', 'Payment received!');
                }
            }

            // FINAL VERIFICATION
            if ($rehabEncounter) {
                $rehabEncounter->refresh();
                \Log::info('FINAL RehabEncounter status: ' . $rehabEncounter->status);
            }
            \Log::info('=== END PAYMENT FLOW ===');
        });

        return redirect()->route('cashier.rehab.payments');

    } catch (\Exception $e) {
        \Log::error('Payment error: ' . $e->getMessage());
        session()->flash('error', 'Error processing payment: ' . $e->getMessage());
        $this->showConfirmModal = false;
    }
}
    public function getPaymentProgressProperty()
    {
        $totalPaid = $this->rehabOrder->paid_amount;
        if ($this->grandTotal > 0) {
            return round(($totalPaid / $this->grandTotal) * 100, 1);
        }
        return 0;
    }

    public function getInstallmentsPaidCountProperty()
    {
        return $this->paymentInstallments->where('status', 'paid')->count();
    }

    public function getInstallmentsTotalCountProperty()
    {
        return $this->paymentInstallments->count();
    }

    public function closePaymentPlanModal()
    {
        $this->showPaymentPlanModal = false;
        $this->paymentType = 'full';
        $this->installmentCount = 2;
        $this->customInstallments = [];
    }

    public function render()
    {
        return view('livewire.rehab.cashier.rehab-payment-process');
    }
}
