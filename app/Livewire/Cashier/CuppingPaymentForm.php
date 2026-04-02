<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\CuppingTherapy;
use App\Models\CuppingPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuppingPaymentForm extends Component
{
    public CuppingTherapy $cupping;
    public $amount = 0;
    public $payment_method = 'cash';
    public $remaining = 0;
    public $total_paid = 0;
    public $full_payment = false;

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'payment_method' => 'required|string|in:cash,card',
    ];

    public function mount(CuppingTherapy $cupping)
    {
        $this->cupping = $cupping;
        $this->calculateRemaining();
    }

    public function calculateRemaining()
    {
        $this->total_paid = $this->cupping->total_paid;
        $this->remaining = $this->cupping->remaining_amount;
        
        if ($this->remaining <= 0) {
            $this->full_payment = true;
        }
    }

    public function updatedAmount()
    {
        if ($this->amount > $this->remaining && $this->remaining > 0) {
            $this->amount = $this->remaining;
        }
        
        if ($this->amount < 0) {
            $this->amount = 0;
        }
    }

    public function processPayment()
    {
        $this->validate();

        if ($this->amount <= 0) {
            $this->addError('amount', 'Amount must be greater than 0.');
            return;
        }

        if ($this->amount > $this->remaining) {
            $this->addError('amount', 'Amount cannot exceed remaining balance.');
            return;
        }

        DB::beginTransaction();

        try {
            $payment = CuppingPayment::create([
                'cupping_therapy_id' => $this->cupping->id,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'received_by' => Auth::id(),
                'status' => CuppingPayment::STATUS_PAID,
                'paid_at' => now(),
            ]);

            // Update therapy status based on payments
            $this->cupping->updateStatusBasedOnPayments();
            
            // Refresh the model
            $this->cupping->refresh();
            $this->calculateRemaining();

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Payment of ' . number_format($this->amount, 2) . ' recorded successfully!');
            
            // Reset amount for next payment
            $this->amount = 0;

            // If fully paid, show success and redirect or stay
            if ($this->remaining <= 0) {
                $this->dispatch('payment-completed');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', message: 'Payment failed: ' . $e->getMessage());
        }
    }

    public function markAsSentToCupping()
    {
        if ($this->cupping->status !== CuppingTherapy::STATUS_PAYMENT_COMPLETED) {
            $this->dispatch('alert', type: 'error', message: 'Payment must be completed before sending to cupping department.');
            return;
        }

        $this->cupping->update(['status' => CuppingTherapy::STATUS_SENT_TO_CUPPING]);
        $this->dispatch('alert', type: 'success', message: 'Therapy sent to cupping department.');
    }

    public function render()
    {
        return view('livewire.cashier.cupping-payment-form', [
            'payments' => $this->cupping->payments()->latest()->get(),
        ]);
    }
}