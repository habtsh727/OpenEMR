<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Patient;

class PaymentDetail extends Component
{

    public Patient $patient;

    public function mount(Patient $patient)
    {
        // Load related payments
        $this->patient = $patient->load('cardPayments');
    }


    public function render()
    {
        return view('livewire.payments.payment-detail');
    }

    
}
