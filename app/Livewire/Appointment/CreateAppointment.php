<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Rules\AvailableTimeSlot;
use App\Services\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateAppointment extends Component
{
    // Form fields
    public $patient_id;
    public $doctor_id;
    public $visit_type = 'consultation';
    public $appointment_date;
    public $appointment_time;
    public $status = 'scheduled';
    public $additional_notes;
    public $payment_status = 'unpaid';
    public $payment_amount;
    public $related_order_type = 'none';
    public $related_order_id;

    // Search
    public $patientSearch = '';
    public $searchResults = [];
    public $showPatientSearch = false;

    // Time slots
    public $availableSlots = [];
    public $selectedSlot = null;

    // Patient selection
    public $selectedPatient = null;

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->appointment_date = now()->format('Y-m-d');
    }

    public function updatedPatientSearch()
    {
        if (strlen($this->patientSearch) > 2) {
            $this->searchResults = Patient::where('first_name', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('middle_name', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('last_name', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('card_number', 'like', '%' . $this->patientSearch . '%')
                ->limit(10)
                ->get();
            $this->showPatientSearch = true;
        } else {
            $this->searchResults = [];
            $this->showPatientSearch = false;
        }
    }

    public function selectPatient($patientId)
    {
        $this->selectedPatient = Patient::find($patientId);
        $this->patient_id = $patientId;
        $this->patientSearch = $this->selectedPatient->first_name . ' ' . $this->selectedPatient->last_name;
        $this->showPatientSearch = false;
    }

    public function updatedDoctorId()
    {
        $this->loadAvailableSlots();
    }

    public function updatedAppointmentDate()
    {
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        if ($this->doctor_id && $this->appointment_date) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->doctor_id,
                $this->appointment_date
            );
        }
    }

    public function selectSlot($slotTime)
    {
        $this->appointment_time = $slotTime;
        $this->selectedSlot = $slotTime;
    }

    public function save()
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => 'required|in:consultation,follow-up,lab_review,rehab_milestone,emergency,other',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => [
                'required',
                new AvailableTimeSlot($this->doctor_id, $this->appointment_date)
            ],
            'payment_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            Appointment::create([
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'visit_type' => $this->visit_type,
                'appointment_date' => $this->appointment_date,
                'appointment_time' => $this->appointment_time,
                'status' => $this->status,
                'additional_notes' => $this->additional_notes,
                'payment_status' => $this->payment_status,
                'payment_amount' => $this->payment_amount,
                'related_order_type' => $this->related_order_type,
                'related_order_id' => $this->related_order_id,
                'created_by' => Auth::id(),
            ]);

            session()->flash('success', 'Appointment created successfully!');
            
            return redirect()->route('appointments.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.appointment.create-appointment', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
        ]);
    }
}