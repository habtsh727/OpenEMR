<?php

namespace App\Livewire\Patients;

use App\Models\CardPayment;
use App\Models\Encounter;
use Livewire\Component;
use Livewire\Attributes\On;
use Flux\Flux;

class AddEncounter extends Component
{
    public $patientId;
    public $requires_card_payment = false;
    public $first_name = null;
    public $middle_name = null;
    public $mother_name = null;
    public $card_number = null;



    #[On('add-encounter')]

    public function addEncounter($id)
    {
        $patient = \App\Models\Patient::find($id);
        $this->patientId = $patient->id;
        $this->first_name = $patient->first_name;
        $this->middle_name = $patient->middle_name;
        $this->last_name = $patient->last_name;
        $this->mother_name = $patient->mother_name;
        $this->card_number = $patient->card_number;
        Flux::modal('add-encounter')->show();
    }
    public function save()
    {
        $latest = Encounter::where('patient_id', $this->patientId)->latest()->first();
        // if ($latest && !in_array($latest->status, ['completed', 'cancelled'])) {
        //     $this->reset();
        //     Flux::modals()->close();

        //     session()->flash('error', 'Previous encounter must be completed or cancelled.');
        //     return;
        // }
        $cardPaymentId = null;
        if ($this->requires_card_payment) {
            $cardPaymentId = CardPayment::create([
                'is_paid' => false,
                'amount' => 200,
                'patient_id' => $this->patientId,
                'payment_date' => now(),
                'processed_by' => auth()->id(),
            ])->id;
        } else {
            Encounter::create([
                'patient_id' => $this->patientId,
                'status' => 'pending',
                'card_payment_id' => $cardPaymentId,
            ]);
        }



        session()->flash('success', 'Encounter created successfully.');
        $this->reset();
        Flux::modals()->close();

        $this->redirectRoute('patients', navigate: true);
    }
    public function render()
    {
        return view('livewire.patients.add-encounter');
    }
}
