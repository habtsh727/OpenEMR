<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\On;
use Flux\Flux;

class PatientDetail extends Component
{

    public $patientId;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $mother_name;
    public $date_of_birth;  
    public $gender;
    public $phone_number1;
    public $phone_number2;
    public $emergency_person;
    public $emergency_contact;
    public $emergency_person_relationship;
    public $region;
    public $zone;
    public $woreda;
    public $card_number;
    public $last_visit_at;
    public $created_at;
    public $updated_at;

    #[On('patient-detail')]

    public function viewDetails($id)
    {
        $patient = \App\Models\Patient::find($id);
        if (!$patient) {
            session()->flash('error', 'Patient not found.');
            return;
        }
        $this->patientId = $patient->id;
        $this->first_name = $patient->first_name;
        $this->middle_name = $patient->middle_name;
        $this->last_name = $patient->last_name;
        $this->mother_name = $patient->mother_name;
        $this->date_of_birth = $patient->date_of_birth;
        $this->gender = $patient->gender;
        $this->phone_number1 = $patient->phone_number1;
        $this->phone_number2 = $patient->phone_number2;
        $this->emergency_person = $patient->emergency_person;
        $this->emergency_contact = $patient->emergency_contact;
        $this->emergency_person_relationship = $patient->emergency_person_relationship;
        $this->region = $patient->region;
        $this->zone = $patient->region_zone;
        $this->woreda = $patient->region_woreda;
        $this->card_number = $patient->card_number;
        $this->last_visit_at = $patient->last_visit_at;
        $this->created_at = $patient->created_at;
        $this->updated_at = $patient->updated_at;
          Flux::modal('patient-detail')->show();

    }
    public function render()
    {
        return view('livewire.patients.patient-detail');
    }
}
