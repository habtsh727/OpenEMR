<?php

namespace App\Livewire\Patients;

use App\Models\CardPayment;
use App\Models\Encounter;
use App\Models\Patient;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Flux\Flux;

class AddEncounter extends Component
{
    public $patientId;
    public $requires_card_payment = false;
    public $first_name = null;
    public $middle_name = null;
    public $last_name = null;
    public $mother_name = null;
    public $card_number = null;

    // Encounter fields
    public $encounter_status = 'pending';
    public $priority = 'medium';
    public $triage_by = null;
    public $doctor_id = null;
    public $payment_amount = 200;

    // Vitals fields
    public $bp_systolic = null;
    public $bp_diastolic = null;
    public $temperature = null;
    public $pulse = null;
    public $spo2 = null;

    // Staff lists
    public $doctors = [];
    public $triageNurses = [];

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

        // Load available staff
        $this->loadStaff();

        Flux::modal('add-encounter')->show();
    }

    protected function loadStaff()
    {
        // Load doctors (users with doctor role)
        $this->doctors = User::whereHas('roles', function ($query) {
            $query->where('name', 'doctor');
        })->get();

        // Load triage nurses (users with nurse role)
        $this->triageNurses = User::whereHas('roles', function ($query) {
            $query->where('name', 'nurse');
        })->get();
    }

    public function save()
    {
        // Validate required fields
        $this->validate([
            'patientId' => 'required|exists:patients,id',
            'encounter_status' => 'required|in:pending,triaged,doctor_assigned',
            'priority' => 'required|in:low,medium,high',
            'payment_amount' => 'nullable|numeric|min:0',
            'bp_systolic' => 'nullable|numeric|min:0|max:300',
            'bp_diastolic' => 'nullable|numeric|min:0|max:200',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pulse' => 'nullable|numeric|min:0|max:200',
            'spo2' => 'nullable|numeric|min:0|max:100',
        ]);

        // Check for active encounters (optional - uncomment if needed)
        // $latest = Encounter::where('patient_id', $this->patientId)
        //     ->whereNotIn('status', ['completed', 'cancelled'])
        //     ->latest()
        //     ->first();

        // if ($latest) {
        //     $this->reset();
        //     Flux::modals()->close();
        //     session()->flash('error', 'Patient has an active encounter. Please complete or cancel it first.');
        //     return;
        // }

        $cardPaymentId = null;

        // Create card payment if required
        if ($this->requires_card_payment) {
            $cardPayment = CardPayment::create([
                'patient_id' => $this->patientId,
                'amount' => $this->payment_amount ?? 200,
                'is_paid' => false,
                'payment_date' => now(),
                'processed_by' => auth()->id(),
            ]);
            $cardPaymentId = $cardPayment->id;
        }

        // Create encounter
        Encounter::create([
            'patient_id' => $this->patientId,
            'doctor_id' => $this->doctor_id ?: null,
            'triage_by' => $this->triage_by ?: null,
            'card_payment_id' => $cardPaymentId,
            'bp_systolic' => $this->bp_systolic,
            'bp_diastolic' => $this->bp_diastolic,
            'temperature' => $this->temperature,
            'pulse' => $this->pulse,
            'spo2' => $this->spo2,
            'priority' => $this->priority,
            'status' => $this->encounter_status,
            'processed_by' => auth()->id(),
        ]);

        // Reset and close
        $this->resetForm();
        Flux::modals()->close();

        session()->flash('success', 'Encounter created successfully.');

        // Redirect or emit event
        $this->dispatch('encounter-created');
        $this->redirectRoute('patients', navigate: true);
    }

    protected function resetForm()
    {
        $this->reset([
            'patientId',
            'requires_card_payment',
            'first_name',
            'middle_name',
            'last_name',
            'mother_name',
            'card_number',
            'encounter_status',
            'priority',
            'triage_by',
            'doctor_id',
            'payment_amount',
            'bp_systolic',
            'bp_diastolic',
            'temperature',
            'pulse',
            'spo2'
        ]);
    }
    public function closeModal()
    {
        $this->dispatch('close-modal', name: 'add-encounter');
    }

    public function render()
    {
        return view('livewire.patients.add-encounter');
    }
}
