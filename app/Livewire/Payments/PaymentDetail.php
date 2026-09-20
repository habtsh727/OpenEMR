<?php

namespace App\Livewire\Payments;

use App\Models\CardPayment;
use App\Models\Encounter;
use Livewire\Component;
use App\Models\Patient;
use Flux\Flux;

class PaymentDetail extends Component
{

    public $paymentId; // The payment ID passed from the page
    public $showCreatePaymentModal = false; // control modal visibility

    // Data for the payment form
    public $amount;
    public $method;
    public $payment_type;

    public Patient $patient;

    public function mount(Patient $patient)
    {
        // Load related payments
        $this->patient = $patient->load('cardPayments');
    }




    protected $rules = [
        'amount' => 'required|numeric',
        'method' => 'required|string',
    ];

    public function payCard($paymentId)
    {
        $this->paymentId = $paymentId;
        $this->showCreatePaymentModal = true;
        $cardPayment = CardPayment::find($paymentId);
        Encounter::create([
            'patient_id' => $cardPayment->patient_id,
            'card_payment_id' => $paymentId,
        ]);
    }


    public function save($id)
    {
        // Create card payment
        $this->validate([
            'payment_type' => 'required|string',
        ]);

        $cardpay = \App\Models\CardPayment::find($id);
        if ($cardpay) {
            $cardpay->update([
                'is_paid' => true,
                'payment_type' => $this->payment_type,
                'payment_date' => now(),
                'processed_by' => auth()->id(),
            ]);
            session()->flash('success', 'Paid successfully.');
            Flux::modals()->close();
            $this->redirectRoute('payments', navigate: true);
        } else {
            session()->flash('error', 'PAyment  not found.');
        }
    }

    public function render()
    {
        return view('livewire.payments.payment-detail');
    }
}
