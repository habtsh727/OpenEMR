<?php
// app/Livewire/Rehab/Cashier/PatientPaymentDetails.php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabPaymentInstallment;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PatientPaymentDetails extends Component
{
    public $order;
    public $orderId;

    // Payment modal properties
    public $showPaymentModal = false;
    public $selectedInstallment = null;
    public $paymentMethod = 'cash';
    public $paymentAmount = 0;
    public $changeAmount = 0;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $listeners = ['refreshPaymentDetails' => '$refresh'];


    #[Computed]
    public function paymentProgress()
    {
        $totalAmount = $this->order->grand_total ?? $this->order->total_amount;
        $paidAmount = $this->order->paid_amount;

        if ($totalAmount > 0) {
            return min(round(($paidAmount / $totalAmount) * 100, 1), 100);
        }
        return 0;
    }

    #[Computed]
    public function paidInstallmentsCount()
    {
        return $this->order->paymentInstallments->where('status', 'paid')->count();
    }

    #[Computed]
    public function totalInstallmentsCount()
    {
        return $this->order->paymentInstallments->count();
    }

    #[Computed]
    public function nextDueDate()
    {
        $nextPending = $this->order->paymentInstallments
            ->where('status', 'pending')
            ->sortBy('installment_number')
            ->first();

        return $nextPending ? $nextPending->due_date : null;
    }

    #[Computed]
    public function nextDueAmount()
    {
        $nextPending = $this->order->paymentInstallments
            ->where('status', 'pending')
            ->sortBy('installment_number')
            ->first();

        return $nextPending ? $nextPending->getRemainingAmount() : 0;
    }
    #[Computed]
public function grandTotal()
{
    $packageTotal = $this->order->total_amount;
    $bedCost = 0;
    
    if ($this->order->bedSelections->isNotEmpty()) {
        $bedSelection = $this->order->bedSelections->first();
        $bedCost = $bedSelection->total_price ?? 0;
    }
    
    return $packageTotal + $bedCost;
}
    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrder();
    }

    public function loadOrder()
    {
        $this->order = RehabOrder::with([
            'encounter.encounter.patient',
            'encounter.encounter.doctor',
            'packages.items',
            'bedSelections' => function ($query) {
                $query->with(['bedClass', 'bed'])->latest();
            },
            'paymentInstallments' => function ($query) {
                $query->orderBy('installment_number');
            }
        ])->findOrFail($this->orderId);
    }

    public function openPaymentModal($installmentId)
    {
        $this->selectedInstallment = RehabPaymentInstallment::find($installmentId);

        if ($this->selectedInstallment) {
            $this->paymentAmount = $this->selectedInstallment->getRemainingAmount();
            $this->changeAmount = 0;
            $this->paymentMethod = 'cash';
            $this->showPaymentModal = true;
        }
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedInstallment = null;
        $this->paymentAmount = 0;
        $this->changeAmount = 0;
        $this->paymentMethod = 'cash';
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
        if (!$this->selectedInstallment) {
            return;
        }

        $remainingAmount = $this->selectedInstallment->getRemainingAmount();

        $this->validate([
            'paymentMethod' => 'required|in:cash,card,insurance,bank_transfer',
            'paymentAmount' => 'required|numeric|min:1|max:' . $remainingAmount,
        ]);

        try {
            DB::transaction(function () {
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
                $totalPaid = $this->order->paymentInstallments()->sum('paid_amount');
                $this->order->paid_amount = $totalPaid;

                // Check if this is the first installment
                $isFirstInstallment = $this->selectedInstallment->installment_number === 1;

                // Check if all installments are paid
                $pendingInstallments = $this->order->paymentInstallments()
                    ->where('status', '!=', 'paid')
                    ->count();

                if ($pendingInstallments === 0) {
                    // ALL INSTALLMENTS PAID
                    $this->order->status = 'paid';
                    $this->order->payment_status = 'paid';
                    $this->order->payment_method = $this->paymentMethod;
                    $this->order->paid_at = now();

                    // Update rehab encounter status
                    if ($this->order->encounter) {
                        $this->order->encounter->update([
                            'status' => 'sent_to_rehab'
                        ]);
                    }

                    // Update bed status
                    if ($this->order->bedSelections->isNotEmpty()) {
                        $bedSelection = $this->order->bedSelections->first();
                        $bed = \App\Models\Bed::find($bedSelection->bed_id);
                        if ($bed) {
                            $bed->update(['status' => 'occupied']);
                        }
                        $bedSelection->update(['status' => 'completed']);
                    }

                    $this->alertMessage = "Full payment completed! Patient sent to rehabilitation.";
                    $this->alertType = 'success';
                } elseif ($isFirstInstallment && $installmentStatus === 'paid') {
                    // FIRST INSTALLMENT FULLY PAID
                    $this->order->payment_status = 'partial';
                    $this->order->status = 'paid';

                    // Update rehab encounter status
                    if ($this->order->encounter) {
                        $this->order->encounter->update([
                            'status' => 'sent_to_rehab'
                        ]);
                    }

                    // Update bed status
                    if ($this->order->bedSelections->isNotEmpty()) {
                        $bedSelection = $this->order->bedSelections->first();
                        $bed = \App\Models\Bed::find($bedSelection->bed_id);
                        if ($bed) {
                            $bed->update(['status' => 'occupied']);
                        }
                        $bedSelection->update(['status' => 'completed']);
                    }

                    // Set next payment due date
                    $nextInstallment = $this->order->paymentInstallments()
                        ->where('installment_number', 2)
                        ->first();

                    if ($nextInstallment) {
                        $this->order->next_payment_due = $nextInstallment->due_date;
                    }

                    $this->alertMessage = "First installment paid! Patient sent to rehabilitation.";
                    $this->alertType = 'success';
                } else {
                    // PARTIAL PAYMENT
                    $nextInstallment = $this->order->paymentInstallments()
                        ->where('status', 'pending')
                        ->orderBy('installment_number')
                        ->first();

                    if ($nextInstallment) {
                        $this->order->next_payment_due = $nextInstallment->due_date;
                    }

                    $this->order->payment_status = 'partial';
                    $this->order->status = 'sent_to_cashier';

                    $this->alertMessage = "Payment of " . number_format($this->paymentAmount, 2) . " ETB received.";
                    $this->alertType = 'success';
                }

                $this->order->save();
            });

            $this->closePaymentModal();
            $this->loadOrder(); // Refresh data
            $this->showAlert = true;
        } catch (\Exception $e) {
            $this->alertMessage = 'Error processing payment: ' . $e->getMessage();
            $this->alertType = 'error';
            $this->showAlert = true;
        }
    }

    public function getPaymentProgressAttribute()
    {
        $totalAmount = $this->order->grand_total ?? $this->order->total_amount;
        $paidAmount = $this->order->paid_amount;

        if ($totalAmount > 0) {
            return round(($paidAmount / $totalAmount) * 100, 1);
        }
        return 0;
    }

    public function getPaidInstallmentsCountAttribute()
    {
        return $this->order->paymentInstallments->where('status', 'paid')->count();
    }

    public function getTotalInstallmentsCountAttribute()
    {
        return $this->order->paymentInstallments->count();
    }

    public function getNextDueDateAttribute()
    {
        $nextPending = $this->order->paymentInstallments
            ->where('status', 'pending')
            ->sortBy('installment_number')
            ->first();

        return $nextPending ? $nextPending->due_date : null;
    }

    public function getNextDueAmountAttribute()
    {
        $nextPending = $this->order->paymentInstallments
            ->where('status', 'pending')
            ->sortBy('installment_number')
            ->first();

        return $nextPending ? $nextPending->getRemainingAmount() : 0;
    }

    public function render()
    {
        return view('livewire.rehab.cashier.patient-payment-details');
    }
}
