<?php
// app/Livewire/Rehab/Cashier/RehabPaymentQueue.php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabPaymentInstallment;
use App\Models\RehabEncounter;
use App\Models\RehabBedSelection;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RehabPaymentQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = 'pending'; // pending, processed, overdue, partial
    public $selectedOrder = null;
    public $showDetailsModal = false;

    // Payment properties
    public $showPaymentModal = false;
    public $paymentOrder = null;
    public $paymentInstallments = [];
    public $selectedInstallment = null;
    public $paymentMethod = 'cash';
    public $paymentAmount = 0;
    public $changeAmount = 0;
    public $partialPaymentNote = '';

    // Bed selection properties
    public $bedSelection = null;
    public $bedCost = 0;
    public $packageTotal = 0;
    public $grandTotal = 0;

    // NEW: Installment creation properties
    public $showPaymentPlanModal = false;
    public $paymentType = 'full'; // 'full' or 'installment'
    public $installmentCount = 2;
    public $customInstallments = [];
    public $totalDurationDays = 0;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    // Payment reminder
    public $paymentReminders = [];

    protected $listeners = ['refreshQueue' => '$refresh'];

    public function mount()
    {
        $this->checkPaymentReminders();
    }

    public function checkPaymentReminders()
    {
        // Check for upcoming payments (5 days before due)
        $upcomingPayments = RehabPaymentInstallment::with(['rehabOrder.encounter.encounter.patient'])
            ->where('status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(5)])
            ->get();

        foreach ($upcomingPayments as $installment) {
            $daysLeft = now()->diffInDays($installment->due_date, false);
            if ($daysLeft <= 5 && $daysLeft >= 0) {
                // FIXED: Use first_name and last_name instead of name
                $patientName = trim(
                    ($installment->rehabOrder->encounter->encounter->patient->first_name ?? '') . ' ' .
                        ($installment->rehabOrder->encounter->encounter->patient->last_name ?? '')
                );

                $this->paymentReminders[] = [
                    'type' => 'warning',
                    'message' => "Payment of " . number_format($installment->amount, 2) . " ETB due in {$daysLeft} days for patient: " . $patientName,
                    'installment_id' => $installment->id,
                    'order_id' => $installment->rehab_order_id,
                    'due_date' => $installment->due_date->format('Y-m-d')
                ];
            }
        }

        // Check for overdue payments
        $overduePayments = RehabPaymentInstallment::with(['rehabOrder.encounter.encounter.patient'])
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overduePayments as $installment) {
            $daysOverdue = now()->diffInDays($installment->due_date);
            // FIXED: Use first_name and last_name instead of name
            $patientName = trim(
                ($installment->rehabOrder->encounter->encounter->patient->first_name ?? '') . ' ' .
                    ($installment->rehabOrder->encounter->encounter->patient->last_name ?? '')
            );

            $this->paymentReminders[] = [
                'type' => 'danger',
                'message' => "OVERDUE by {$daysOverdue} days: " . number_format($installment->amount, 2) . " ETB for patient: " . $patientName,
                'installment_id' => $installment->id,
                'order_id' => $installment->rehab_order_id,
                'due_date' => $installment->due_date->format('Y-m-d')
            ];

            // Mark order as overdue
            $installment->rehabOrder->update(['payment_status' => 'overdue']);
        }
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages.items',
            'encounter.encounter.doctor',
            'bedSelections' => function ($query) {
                $query->with(['bedClass', 'bed'])->latest();
            },
            'paymentInstallments'
        ])->find($orderId);

        // Calculate bed cost if exists
        if ($this->selectedOrder && $this->selectedOrder->bedSelections->isNotEmpty()) {
            $this->bedSelection = $this->selectedOrder->bedSelections->first();
            $this->bedCost = $this->bedSelection->total_price ?? 0;
        }

        $this->showDetailsModal = true;
    }

    public function openPaymentModal($orderId)
    {
        $this->paymentOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages',
            'bedSelections' => function ($query) {
                $query->with(['bedClass', 'bed'])->latest();
            },
            'paymentInstallments'
        ])->find($orderId);

        if (!$this->paymentOrder) {
            $this->showAlertMessage('Order not found', 'error');
            return;
        }

        // Calculate package total
        $this->packageTotal = $this->paymentOrder->total_amount;

        // Get bed selection and cost
        $this->bedSelection = $this->paymentOrder->bedSelections->first();
        $this->bedCost = $this->bedSelection->total_price ?? 0;

        // Calculate grand total
        $this->grandTotal = $this->packageTotal + $this->bedCost;

        // Get bed duration for installment calculation
        if ($this->bedSelection) {
            $this->totalDurationDays = $this->bedSelection->duration_days ?? 0;
        }

        // Get pending installments
        $this->paymentInstallments = $this->paymentOrder->paymentInstallments()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('installment_number')
            ->get();

        // If no installments exist, show payment plan creation modal
        if ($this->paymentInstallments->isEmpty()) {
            // DON'T close payment modal, just show payment plan modal
            // Keep paymentOrder reference
            $this->initializeCustomInstallments();
            $this->showPaymentPlanModal = true;
            return;
        }

        // Auto-select first pending installment
        $this->selectedInstallment = $this->paymentInstallments->first();

        if ($this->selectedInstallment) {
            $this->paymentAmount = $this->selectedInstallment->getRemainingAmount();
        } else {
            $this->paymentAmount = 0;
        }

        $this->changeAmount = 0;
        $this->paymentMethod = 'cash';
        $this->showPaymentModal = true;

        if ($this->showDetailsModal) {
            $this->closeModal();
        }
    }

    // NEW: Open payment plan modal for creating installments
    public function openPaymentPlanModal()
    {
        $this->initializeCustomInstallments();
        $this->showPaymentPlanModal = true;
    }

    // NEW: Initialize custom installments
    public function initializeCustomInstallments()
    {
        $this->customInstallments = [];
        $totalAmount = $this->grandTotal;

        // Default to 2 installments with 50% each
        for ($i = 1; $i <= $this->installmentCount; $i++) {
            $amount = $totalAmount / $this->installmentCount;

            // Calculate due date based on duration
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

        // Adjust last installment to ensure total matches exactly
        $total = array_sum(array_column($this->customInstallments, 'amount'));
        if (abs($total - $totalAmount) > 0.01) {
            $lastIndex = count($this->customInstallments) - 1;
            $this->customInstallments[$lastIndex]['amount'] = round(
                $this->customInstallments[$lastIndex]['amount'] + ($totalAmount - $total),
                2
            );
        }
    }

    // NEW: Update installment count
    public function updatedInstallmentCount()
    {
        $this->initializeCustomInstallments();
    }

    // NEW: Update specific installment amount
    public function updateInstallmentAmount($index, $amount)
    {
        if ($index >= 0 && $index < count($this->customInstallments)) {
            $this->customInstallments[$index]['amount'] = round(floatval($amount), 2);

            // Recalculate percentages
            $total = array_sum(array_column($this->customInstallments, 'amount'));
            foreach ($this->customInstallments as $i => $installment) {
                $this->customInstallments[$i]['percentage'] = round(($installment['amount'] / $total) * 100, 2);
            }
        }
    }

    // NEW: Add new installment
    public function addInstallment()
    {
        $this->installmentCount++;
        $totalAmount = $this->grandTotal;

        // Calculate new due date
        $dueDate = now();
        if ($this->totalDurationDays > 0) {
            $daysPerInstallment = floor($this->totalDurationDays / $this->installmentCount);
            $dueDate = now()->addDays($daysPerInstallment * $this->installmentCount);
        }

        $this->customInstallments[] = [
            'number' => $this->installmentCount,
            'amount' => 0,
            'due_date' => $dueDate->format('Y-m-d'),
            'percentage' => 0
        ];

        // Redistribute amounts
        $this->redistributeInstallments();
    }

    // NEW: Remove last installment
    public function removeInstallment()
    {
        if ($this->installmentCount > 1) {
            array_pop($this->customInstallments);
            $this->installmentCount--;
            $this->redistributeInstallments();
        }
    }

    // NEW: Redistribute installments to match total
    private function redistributeInstallments()
    {
        $totalAmount = $this->grandTotal;
        $total = array_sum(array_column($this->customInstallments, 'amount'));

        if ($total != $totalAmount) {
            $perInstallment = floor(($totalAmount / $this->installmentCount) * 100) / 100;

            for ($i = 0; $i < $this->installmentCount; $i++) {
                if ($i == $this->installmentCount - 1) {
                    // Last installment gets the remainder
                    $this->customInstallments[$i]['amount'] = $totalAmount - ($perInstallment * ($this->installmentCount - 1));
                } else {
                    $this->customInstallments[$i]['amount'] = $perInstallment;
                }
                $this->customInstallments[$i]['percentage'] = round(($this->customInstallments[$i]['amount'] / $totalAmount) * 100, 2);
            }
        }
    }

    // NEW: Create installment schedule
    public function createInstallmentSchedule()
    {
        try {
            $message = ''; // Declare message variable outside transaction

            DB::transaction(function () use (&$message) { // Pass by reference
                // Delete any existing installments
                RehabPaymentInstallment::where('rehab_order_id', $this->paymentOrder->id)->delete();

                if ($this->paymentType === 'installment') {
                    // Create custom installments
                    foreach ($this->customInstallments as $installment) {
                        RehabPaymentInstallment::create([
                            'rehab_order_id' => $this->paymentOrder->id,
                            'installment_number' => $installment['number'],
                            'amount' => $installment['amount'],
                            'due_date' => $installment['due_date'],
                            'status' => $installment['number'] === 1 ? 'pending' : 'pending',
                            'created_by' => auth()->id()
                        ]);
                    }

                    // Update order with installment info
                    $this->paymentOrder->update([
                        'payment_type' => 'installment',
                        'installment_count' => $this->installmentCount,
                        'paid_amount' => 0,
                        'payment_status' => 'pending',
                        'next_payment_due' => $this->customInstallments[0]['due_date'] ?? now()
                    ]);

                    $message = 'Installment plan created successfully with ' . $this->installmentCount . ' payments.';
                } else {
                    // Full payment - create single installment
                    RehabPaymentInstallment::create([
                        'rehab_order_id' => $this->paymentOrder->id,
                        'installment_number' => 1,
                        'amount' => $this->grandTotal,
                        'due_date' => now(),
                        'status' => 'pending',
                        'created_by' => auth()->id()
                    ]);

                    $this->paymentOrder->update([
                        'payment_type' => 'full',
                        'installment_count' => 1,
                        'paid_amount' => 0,
                        'payment_status' => 'pending',
                        'next_payment_due' => now()
                    ]);

                    $message = 'Full payment plan created.';
                }
            });

            $this->closePaymentPlanModal();

            // Re-open payment modal with the new installments
            $this->openPaymentModal($this->paymentOrder->id);

            $this->showAlertMessage($message, 'success');
        } catch (\Exception $e) {
            $this->showAlertMessage('Error creating payment plan: ' . $e->getMessage(), 'error');
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
    if (!$this->paymentOrder || !$this->selectedInstallment) {
        return;
    }

    $remainingAmount = $this->selectedInstallment->getRemainingAmount();

    $this->validate([
        'paymentMethod' => 'required|in:cash,card,insurance,bank_transfer',
        'paymentAmount' => 'required|numeric|min:1|max:' . $remainingAmount,
    ]);

    try {
        DB::transaction(function () {
            // Get the rehab encounter FIRST
            $rehabEncounter = $this->paymentOrder->encounter;
            $currentEncounterStatus = $rehabEncounter ? $rehabEncounter->status : null;

            \Log::info('=== PROCESSING PAYMENT ===');
            \Log::info('Order ID: ' . $this->paymentOrder->id);
            \Log::info('Installment number: ' . $this->selectedInstallment->installment_number);
            \Log::info('Current encounter status: ' . $currentEncounterStatus);
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

            // Update order paid amount
            $totalPaid = $this->paymentOrder->paymentInstallments()->sum('paid_amount');
            $this->paymentOrder->paid_amount = $totalPaid;

            // Check if all installments are paid
            $pendingInstallments = $this->paymentOrder->paymentInstallments()
                ->where('status', '!=', 'paid')
                ->count();

            // Determine if treatment has already started
            $isTreatmentStarted = in_array($currentEncounterStatus, ['treatment_in_progress', 'completed']);

            if ($pendingInstallments === 0) {
                // ALL INSTALLMENTS PAID - Final payment
                \Log::info('Processing FINAL payment - All installments paid');

                $this->paymentOrder->status = 'paid';
                $this->paymentOrder->payment_status = 'paid';
                $this->paymentOrder->payment_method = $this->paymentMethod;
                $this->paymentOrder->paid_at = now();

                // ✅ CRITICAL FIX: Only update rehab encounter if treatment hasn't started yet
                if ($rehabEncounter) {
                    if ($isTreatmentStarted) {
                        // Treatment already in progress or completed - PRESERVE the status
                        \Log::info('Final payment - Treatment already started/completed. Preserving status: ' . $currentEncounterStatus);
                        // DO NOT change the status - keep as treatment_in_progress or completed
                    } else {
                        // Treatment not started yet (unlikely for final payment, but handle anyway)
                        \Log::info('Final payment - Treatment not started. Sending to rehab');
                        $rehabEncounter->update(['status' => 'sent_to_rehab']);
                    }
                }

                // If there's a bed selection, update bed status (only if not already done)
                if ($this->bedSelection && $this->bedSelection->status !== 'completed') {
                    $bed = \App\Models\Bed::find($this->bedSelection->bed_id);
                    if ($bed && $bed->status !== 'occupied') {
                        $bed->update(['status' => 'occupied']);
                    }
                    $this->bedSelection->update(['status' => 'completed']);
                }

                $this->showAlertMessage("Final payment completed! Treatment continues.", 'success');

            } else {
                // NOT ALL INSTALLMENTS PAID YET
                \Log::info('Not all installments paid - remaining: ' . $pendingInstallments);

                // Send to rehab if this is the first payment and patient isn't in treatment yet
                $shouldSendToRehab = false;

                if ($rehabEncounter && $currentEncounterStatus === 'sent_to_cashier' && !$isTreatmentStarted) {
                    $shouldSendToRehab = true;
                    \Log::info('Sending patient to rehab (first time)');
                }

                if ($shouldSendToRehab) {
                    $rehabEncounter->update(['status' => 'sent_to_rehab']);
                    $rehabEncounter->refresh();
                    \Log::info('Encounter status after update: ' . $rehabEncounter->status);

                    $this->showAlertMessage(
                        "Payment received! Patient has been sent to rehabilitation.",
                        'success'
                    );
                } else {
                    \Log::info('Patient already in treatment or not ready. Current status: ' . $currentEncounterStatus);
                    $this->showAlertMessage("Payment received successfully!", 'success');
                }

                // Update order status for non-final payments
                $this->paymentOrder->payment_status = 'partial';
                $this->paymentOrder->status = 'sent_to_cashier';

                // Set next payment due date
                $nextInstallment = $this->paymentOrder->paymentInstallments()
                    ->where('status', 'pending')
                    ->orderBy('installment_number')
                    ->first();

                if ($nextInstallment) {
                    $this->paymentOrder->next_payment_due = $nextInstallment->due_date;
                }
            }

            $this->paymentOrder->save();

            // Final verification
            if ($rehabEncounter) {
                $rehabEncounter->refresh();
                \Log::info('FINAL encounter status: ' . $rehabEncounter->status);
            }
        });

        $this->closePaymentModal();
        $this->dispatch('refreshQueue');
        $this->checkPaymentReminders();

    } catch (\Exception $e) {
        \Log::error('Payment error: ' . $e->getMessage());
        $this->showAlertMessage('Error processing payment: ' . $e->getMessage(), 'error');
    }
}
    public function getUpcomingPayments()
    {
        $upcomingPayments = RehabPaymentInstallment::with([
            'rehabOrder.encounter.encounter.patient',
            'rehabOrder.encounter.encounter.doctor'
        ])
            ->where('status', 'pending')
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date')
            ->get();

        return $upcomingPayments;
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrder = null;
        $this->bedSelection = null;
        $this->bedCost = 0;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentOrder = null;
        $this->paymentInstallments = [];
        $this->selectedInstallment = null;
        $this->bedSelection = null;
        $this->bedCost = 0;
        $this->packageTotal = 0;
        $this->grandTotal = 0;
        $this->paymentMethod = 'cash';
        $this->paymentAmount = 0;
        $this->changeAmount = 0;
        $this->partialPaymentNote = '';
    }

    public function closePaymentPlanModal()
    {
        $this->showPaymentPlanModal = false;
        $this->paymentType = 'full';
        $this->installmentCount = 2;
        $this->customInstallments = [];
        // DON'T clear $paymentOrder here
    }

    public function render()
    {
        // Base query
        $query = RehabOrder::with([
            'encounter.encounter.patient',
            'encounter.encounter.doctor',
            'packages',
            'bedSelections' => function ($query) {
                $query->with(['bedClass'])->latest();
            },
            'paymentInstallments'
        ]);

        if ($this->tab === 'pending') {
            // Orders with no payments yet
            $query->whereIn('status', ['sent_to_cashier', 'bed_selected'])
                ->where(function ($q) {
                    $q->where('payment_status', 'pending')
                        ->orWhereNull('payment_status');
                });
        } elseif ($this->tab === 'partial') {
            // Orders with partial payments - check payment_status, NOT status
            $query->where('payment_status', 'partial')
                ->whereIn('status', ['sent_to_cashier', 'paid']); // Include both for now

        } elseif ($this->tab === 'overdue') {
            // Overdue payments
            $query->where('payment_status', 'overdue')
                ->whereIn('status', ['sent_to_cashier', 'paid']);
        } elseif ($this->tab === 'processed') {
            // FULLY PAID and completed orders
            $query->where('status', 'paid')
                ->where('payment_status', 'paid');
        }

        // Apply search filter - FIXED: Using first_name, last_name, and card_number
        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->whereHas('encounter.encounter.patient', function ($patient) use ($searchTerm) {
                $patient->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('card_number', 'like', $searchTerm);
            });
        }

        // Order by latest
        $query->latest();

        // Paginate
        $orders = $query->paginate(10);

        // Calculate totals with bed cost and payment progress for each order
        foreach ($orders as $order) {
            $order->bed_cost = 0;
            $order->bed_duration = 0;
            $order->bed_class_name = null;

            if ($order->bedSelections->isNotEmpty()) {
                $bedSelection = $order->bedSelections->first();
                $order->bed_cost = $bedSelection->total_price ?? 0;
                $order->bed_duration = $bedSelection->duration_days ?? 0;
                $order->bed_class_name = $bedSelection->bedClass->name ?? null;
            }

            $order->grand_total = $order->total_amount + $order->bed_cost;

            // Calculate payment progress correctly
            if ($order->grand_total > 0) {
                $order->payment_progress = round(($order->paid_amount / $order->grand_total) * 100, 1);
            } else {
                $order->payment_progress = 0;
            }

            // Add installment stats for display
            if ($order->paymentInstallments->isNotEmpty()) {
                $order->total_installments = $order->paymentInstallments->count();
                $order->paid_installments = $order->paymentInstallments->where('status', 'paid')->count();
            }
        }

        $upcomingPayments = $this->getUpcomingPayments();

        return view('livewire.rehab.cashier.rehab-payment-queue', [
            'orders' => $orders,
            'paymentReminders' => $this->paymentReminders,
            'upcomingPayments' => $upcomingPayments
        ]);
    }
}
