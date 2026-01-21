<?php

namespace App\Livewire\Patients;

use App\Models\CardPayment;
use App\Models\Encounter;
use Livewire\Component;
use Flux\Flux;
use Illuminate\Support\Facades\DB;




class CreatePatients extends Component
{

    public $selectedId;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $mother_name;
    public $card_number;
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
    public $description;
    public $price;

    public function save()
    {
        //  $cardFee = \App\Models\CardFee::where('is_active', true)->first();
        //  $amount = $cardFee->amount;
        //  $validatedData = $this->validate([

        //     'first_name' => 'required|string|max:255',
        //     'middle_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'mother_name' => 'nullable|string|max:255',
        //     'date_of_birth' => 'required|date', 
        //     'gender' => 'required|string',
        //     'phone_number1' => 'required|string|max:20',
        //     'phone_number2' => 'nullable|string|max:20',
        //     'emergency_person' => 'nullable|string|max:255',
        //     'emergency_contact' => 'nullable|string|max:20',
        //     'emergency_person_relationship' => 'nullable|string|max:100',
        //     'region' => 'nullable|string|max:100',
        //     'zone' => 'nullable|string|max:100',
        //     'woreda' => 'nullable|string|max:100',
        //  ]);
        //cared number unique validation , and should be created sequentially FCHC-yyyy-00001
        //impementing card number generation
        $cardFee = \App\Models\CardFee::where('is_active', true)->first();

        // if no active card fee, set default amount to 0
        $amount = $cardFee ? $cardFee->amount : 0;

        $validatedData = $this->validate([
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

        $lastPatient = \App\Models\Patient::orderBy('id', 'desc')->first();
        if ($lastPatient) {
            $lastCardNumber = $lastPatient->card_number;
            $lastNumber = (int) substr($lastCardNumber, strrpos($lastCardNumber, '-') + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $this->card_number = 'FCHC-' . date('Y') . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);


        DB::beginTransaction();

        try {

            // Create patient
            $patient = \App\Models\Patient::create([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'mother_name' => $this->mother_name,
                'card_number' => $this->card_number,
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
                'created_by' => auth()->id(),
                'last_visit_at' => now(),
            ]);

            // Create card payment
            $cardPayment = CardPayment::create([
                'patient_id' => $patient->id,
                'amount' => $amount,
                'payment_date' => now(),
                'processed_by' => auth()->id(),
            ]);
            Encounter::create([
                'patient_id' => $patient->id,
                'card_payment_id' => $cardPayment->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            return;
        }


        session()->flash('success', 'Patient created successfully.');
        $this->reset();
        Flux::modals()->close();

        $this->redirectRoute('patients', navigate: true);
    }


    public function render()
    {
        return view('livewire.patients.create-patients');
    }
}
