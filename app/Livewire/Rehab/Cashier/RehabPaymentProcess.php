<?php

namespace App\Livewire\Rehab\Cashier;
use App\Models\RehabOrder;
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

    public function mount($rehabOrder)
    {
        $this->rehabOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages.items'
        ])->findOrFail($rehabOrder);

        $this->paymentAmount = $this->rehabOrder->total_amount;
    }

    public function updatedPaymentAmount()
    {
        if ($this->paymentAmount >= $this->rehabOrder->total_amount) {
            $this->changeAmount = $this->paymentAmount - $this->rehabOrder->total_amount;
        } else {
            $this->changeAmount = 0;
        }
    }

    public function processPayment()
    {
        $this->validate([
            'paymentMethod' => 'required|in:cash,card,insurance',
            'paymentAmount' => 'required|numeric|min:' . $this->rehabOrder->total_amount,
        ]);

        $this->showConfirmModal = true;
    }

    public function confirmPayment()
    {
        DB::transaction(function () {
            // Update order status
            $this->rehabOrder->update([
                'status' => 'paid',
                'payment_method' => $this->paymentMethod,
                'paid_at' => now()
            ]);

            // Update rehab encounter status to send back to rehab department
            $this->rehabOrder->encounter->update([
                'status' => 'submitted_to_doctor' // This sends it back to rehab department
            ]);
        });

        session()->flash('message', 'Payment processed successfully! Order sent to rehab department.');
        return redirect()->route('cashier.rehab.payments');
    }

    public function render()
    {
        return view('livewire.rehab.cashier.rehab-payment-process');
    }
}
