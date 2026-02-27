<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Encounter;
use App\Rules\AvailableTimeSlot;
use App\Services\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateAppointment extends Component
{
    // Form fields
    public $patient_id;
    public $doctor_id;
    public $visit_type = 'consultation';
    public $appointment_date;
    public $appointment_time;
    public $time_slot;
    public $status = 'scheduled';
    public $additional_notes;
    public $payment_status = 'unpaid';
    public $payment_amount;
    public $related_order_type = 'none';
    public $related_order_id;
    public $is_request = false;

    // Patient search
    public $patientSearch = '';
    public $searchResults = [];
    public $showPatientSearch = false;
    public $selectedPatient = null;
    public $quickPatientMode = false;
    
    // Quick patient creation
    public $first_name = '';
    public $middle_name = '';
    public $last_name = '';
    public $mother_name = '';
    public $gender = '';
    public $phone_number1 = '';
    public $phone_number2 = '';
    public $emergency_person = '';
    public $emergency_contact = '';
    public $emergency_person_relationship = '';
    public $region = '';
    public $region_zone = '';
    public $region_woreda = '';
    public $date_of_birth = '';

    // Time slots
    public $availableSlots = [];
    public $selectedSlot = null;

    // Related orders
    public $relatedOrders = [];
    public $showRelatedOrderSearch = false;

    // Success/Error
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
        $this->appointment_date = now()->format('Y-m-d');
    }

    // Patient Search Methods
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

    public function toggleQuickPatient()
    {
        $this->quickPatientMode = !$this->quickPatientMode;
        $this->resetQuickPatientForm();
    }

    public function resetQuickPatientForm()
    {
        $this->reset([
            'first_name', 'middle_name', 'last_name', 'mother_name', 'gender',
            'phone_number1', 'phone_number2', 'emergency_person', 'emergency_contact',
            'emergency_person_relationship', 'region', 'region_zone', 'region_woreda', 'date_of_birth'
        ]);
    }

    public function saveQuickPatient()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'phone_number1' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        try {
            $patient = Patient::create([
                'card_number' => 'PAT-' . time(),
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'mother_name' => $this->mother_name,
                'gender' => $this->gender,
                'phone_number1' => $this->phone_number1,
                'phone_number2' => $this->phone_number2,
                'emergency_person' => $this->emergency_person,
                'emergency_contact' => $this->emergency_contact,
                'emergency_person_relationship' => $this->emergency_person_relationship,
                'region' => $this->region,
                'region_zone' => $this->region_zone,
                'region_woreda' => $this->region_woreda,
                'date_of_birth' => $this->date_of_birth,
                'created_by' => Auth::id(),
            ]);

            $this->selectedPatient = $patient;
            $this->patient_id = $patient->id;
            $this->patientSearch = $patient->first_name . ' ' . $patient->last_name;
            $this->quickPatientMode = false;
            $this->resetQuickPatientForm();
            
            $this->showAlertMessage('Patient created successfully!', 'success');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error creating patient: ' . $e->getMessage(), 'error');
        }
    }

    // Doctor and Time Slot Methods
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
        
        // Find the selected slot display
        foreach ($this->availableSlots as $slot) {
            if ($slot['time'] === $slotTime) {
                $this->time_slot = $slot['display'];
                break;
            }
        }
    }

    // Related Order Methods
    public function updatedRelatedOrderType()
    {
        $this->relatedOrders = [];
        $this->related_order_id = null;
        
        if ($this->related_order_type !== 'none') {
            $this->loadRelatedOrders();
        }
    }

    protected function loadRelatedOrders()
    {
        // This would depend on your order types
        // Example for lab orders:
        if ($this->related_order_type === 'lab') {
            $this->relatedOrders = []; // Fetch lab orders
        } elseif ($this->related_order_type === 'medication') {
            $this->relatedOrders = []; // Fetch medication orders
        } elseif ($this->related_order_type === 'rehab') {
            $this->relatedOrders = []; // Fetch rehab orders
        }
    }

    // Validation Rules
    protected function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'visit_type' => 'required|in:consultation,follow-up,lab_review,rehab_milestone,emergency,other',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => [
                'required',
                new AvailableTimeSlot($this->doctor_id, $this->appointment_date)
            ],
            'payment_amount' => 'nullable|numeric|min:0',
            'additional_notes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages()
    {
        return [
            'patient_id.required' => 'Please select a patient',
            'doctor_id.required' => 'Please select a doctor',
            'appointment_date.required' => 'Please select an appointment date',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past',
            'appointment_time.required' => 'Please select an appointment time',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            $appointment = Appointment::create([
                'patient_id' => $this->patient_id,
                'doctor_id' => $this->doctor_id,
                'visit_type' => $this->visit_type,
                'appointment_date' => $this->appointment_date,
                'appointment_time' => $this->appointment_time,
                'time_slot' => $this->time_slot,
                'status' => $this->is_request ? 'requested' : 'scheduled',
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
            $this->showAlertMessage('Failed to create appointment: ' . $e->getMessage(), 'error');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.appointment.create-appointment', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
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
                'partially_paid' => 'Partially Paid',
                'paid' => 'Paid',
                'waived' => 'Waived',
            ],
            'orderTypes' => [
                'none' => 'None',
                'lab' => 'Lab Order',
                'medication' => 'Medication Order',
                'rehab' => 'Rehab Order',
            ],
        ]);
    }
}