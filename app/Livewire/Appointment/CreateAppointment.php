<?php
// app/Livewire/Appointment/CreateAppointment.php

namespace App\Livewire\Appointment;

use App\Models\Patient;
use App\Models\User;
use App\Services\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this import
use Carbon\Carbon;

class CreateAppointment extends Component
{
    public $patient_id;
    public $visit_type = 'consultation';
    public $appointment_date;
    public $appointment_time;
    public $additional_notes;
    public $payment_status = 'unpaid';
    public $payment_amount;
    public $related_order_type = 'none';
    public $related_order_id;

    // Patient search
    public $patientSearch = '';
    public $searchResults = [];
    public $showPatientSearch = false;
    public $selectedPatient = null;

    // Time slots
    public $availableSlots = [];
    public $selectedSlot = null;
    public $loadingSlots = false; // Add loading state

    // UI states
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->appointment_date = Carbon::now('Africa/Addis_Ababa')->format('Y-m-d');
        Log::info('Mounting CreateAppointment', [
            'date' => $this->appointment_date, 
            'doctor_id' => Auth::id()
        ]);
        $this->loadAvailableSlots();
    }

    // Patient Search
    public function updatedPatientSearch()
    {
        if (strlen($this->patientSearch) > 2) {
            $this->searchResults = Patient::where('first_name', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('middle_name', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('last_name', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('card_number', 'like', '%' . $this->patientSearch . '%')
                ->orWhere('phone_number1', 'like', '%' . $this->patientSearch . '%')
                ->limit(10)
                ->get()
                ->map(function ($patient) {
                    return [
                        'id' => $patient->id,
                        'name' => $patient->first_name . ' ' . $patient->middle_name . ' ' . $patient->last_name,
                        'card_number' => $patient->card_number,
                        'phone' => $patient->phone_number1,
                        'gender' => $patient->gender,
                        'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    ];
                });
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

    public function clearPatient()
    {
        $this->selectedPatient = null;
        $this->patient_id = null;
        $this->patientSearch = '';
    }

    // Time Slot Methods
    public function updatedAppointmentDate()
    {
        $this->loadAvailableSlots();
        $this->selectedSlot = null;
        $this->appointment_time = null;
    }

    protected function loadAvailableSlots()
    {
        $this->loadingSlots = true;
        
        if ($this->appointment_date) {
            Log::info('Loading slots', [
                'date' => $this->appointment_date, 
                'doctor_id' => Auth::id()
            ]);
            
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                Auth::id(),
                $this->appointment_date
            );
            
            Log::info('Slots loaded', [
                'count' => count($this->availableSlots)
            ]);
        }
        
        $this->loadingSlots = false;
    }

    public function selectSlot($slotTime)
    {
        $this->appointment_time = $slotTime;
        $this->selectedSlot = $slotTime;
    }

    // Validation
    protected function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'visit_type' => 'required|in:consultation,follow-up,lab_review,rehab_milestone,emergency,other',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'payment_amount' => 'nullable|numeric|min:0',
            'additional_notes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'patient_id.required' => 'Please select a patient',
            'appointment_date.required' => 'Please select a date',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past',
            'appointment_time.required' => 'Please select a time slot',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            // Double-check slot is still available
            if (!$this->appointmentService->isSlotAvailable(
                Auth::id(), 
                $this->appointment_date, 
                $this->appointment_time
            )) {
                $this->alertMessage = 'This time slot is no longer available. Please select another.';
                $this->alertType = 'error';
                $this->showAlert = true;
                
                // Reload available slots
                $this->loadAvailableSlots();
                return;
            }

            $data = [
                'patient_id' => $this->patient_id,
                'doctor_id' => Auth::id(),
                'visit_type' => $this->visit_type,
                'appointment_date' => $this->appointment_date,
                'appointment_time' => $this->appointment_time,
                'additional_notes' => $this->additional_notes,
                'payment_status' => $this->payment_status,
                'payment_amount' => $this->payment_amount,
                'related_order_type' => $this->related_order_type === 'none' ? null : $this->related_order_type,
                'related_order_id' => $this->related_order_id,
            ];

            $appointment = $this->appointmentService->createAppointment($data, Auth::id());

            session()->flash('success', 'Appointment created successfully for ' . 
                $appointment->appointment_time->format('h:i A') . ' on ' . 
                $appointment->appointment_date->format('M d, Y'));
            
            return redirect()->route('doctor.appointments.today');

        } catch (\Exception $e) {
            Log::error('Appointment creation failed', ['error' => $e->getMessage()]);
            
            $this->alertMessage = 'Failed to create appointment: ' . $e->getMessage();
            $this->alertType = 'error';
            $this->showAlert = true;
            
            // Reload slots in case of error
            $this->loadAvailableSlots();
        }
    }

    public function render()
    {
        return view('livewire.appointment.create-appointment', [
            'visitTypes' => [
                'consultation' => 'Consultation',
                'follow-up' => 'Follow-up',
                'lab_review' => 'Lab Review',
                'rehab_milestone' => 'Rehab Milestone',
                'emergency' => 'Emergency',
                'other' => 'Other',
            ],
            'paymentStatuses' => [
                'unpaid' => 'Unpaid',
                'paid' => 'Paid',
                'waived' => 'Waived',
            ],
        ]);
    }
}