<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\Attributes\On;
use Flux\Flux;


class EditPatients extends Component
{


    public $selectedId;
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

   #[On('edit-patients')]
    public function edit($id){
        $patient = \App\Models\Patient::find($id);
        if (!$patient) {
            session()->flash('error', 'Patient not found.');
            return;
        }
        $this->selectedId = $patient->id;
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
          Flux::modal('edit-patients')->show(); 
    }


    public function update(){
        $this->validate([

            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date', 
            'gender' => 'required|string',
            'phone_number1' => 'required|string|max:20',
            'phone_number2' => 'nullable|string|max:20',
            'emergency_person' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:20',
            'emergency_person_relationship' => 'nullable|string|max:100',
            'region' => 'nullable|string|max:100',
            'zone' => 'nullable|string|max:100',
            'woreda' => 'nullable|string|max:100',
         ]);
    
        $patient = \App\Models\Patient::find($this->selectedId);
        if ($patient) {
            $patient->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'mother_name' => $this->mother_name,
                'date_of_birth' => $this->date_of_birth,
                'gender' => $this->gender,
                'phone_number1' => $this->phone_number1,
                'phone_number2' => $this->phone_number2,
                'emergency_person' => $this->emergency_person,
                'emergency_contact' => $this->emergency_contact,
                'emergency_person_relationship' => $this->emergency_person_relationship,
                'region' => $this->region,
                'region_zone' => $this->zone,
                'region_woreda' => $this->woreda,
            ]);
            session()->flash('success', 'Patient updated successfully.');
            Flux::modal('edit-patients')->close();
            $this->redirectRoute('patients',navigate: true);
        } else {
            session()->flash('error', 'Patient not found.');    
        }
    }

    public function render()
    {
        return view('livewire.patients.edit-patients');
    }
}
