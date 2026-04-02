<?php

namespace App\Livewire\Cashier;


use Livewire\Component;
use App\Models\CuppingSession;
use App\Models\CuppingQueue;
use Illuminate\Support\Facades\Auth;

class CuppingPaymentForm extends Component
{

    public CuppingSession $session;
    public $amount = 0;
    public $payment_method = 'cash';
    public $remaining = 0;
    public $session_amount = 0;
    public $paid_amount = 0;

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'payment_method' => 'required|in:cash,card,bank_transfer',
    ];

    public function mount(CuppingSession $session)
    {
        $this->session = $session;
        $this->session_amount = $session->session_amount;
        $this->paid_amount = $session->paid_amount;
        $this->remaining = $session->remaining_amount;
        $this->amount = $this->remaining;
    }

    public function updatedAmount()
    {
        if ($this->amount > $this->remaining) {
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
            $this->addError('amount', 'Amount must be greater than 0');
            return;
        }

        // Process payment
        $this->session->markPaymentComplete($this->amount, $this->payment_method, Auth::id());

        // Remove from payment queue
        if ($this->session->payment_status === 'paid') {
            CuppingQueue::where('cupping_session_id', $this->session->id)
                ->where('queue_type', 'payment')
                ->delete();
        }

        $message = $this->session->payment_status === 'paid'
            ? "Payment completed! Session has been moved to treatment queue."
            : "Partial payment of " . number_format($this->amount, 2) . " recorded.";

        $this->dispatch('alert', type: 'success', message: $message);

        return redirect()->route('cashier.queue');
    }


    public function render()
    {
        return view('livewire.cashier.cupping-payment-form');
    }
}
