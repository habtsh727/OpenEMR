<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient;
use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class AppointmentRequests extends Component
{
    use WithPagination;

    public $search = '';
    public $doctorId = '';
    public $visitType = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;
    
    // Modals
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showRescheduleModal = false;
    public $showDetailsModal = false;
    public $selectedAppointment = null;
    
    // Approve/Reject
    public $approveDate;
    public $approveTime;
    public $approveDoctorId;
    public $availableSlots = [];
    public $rejectionReason = '';
    
    // Statistics
    public $stats = [];

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->addMonth()->format('Y-m-d');
        $this->calculateStats();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDoctorId()
    {
        $this->resetPage();
    }

    public function updatedVisitType()
    {
        $this->resetPage();
    }

    public function calculateStats()
    {
        $query = Appointment::where('status', 'requested');

        $this->stats = [
            'total' => $query->count(),
            'today' => (clone $query)->whereDate('appointment_date', now()->toDateString())->count(),
            'this_week' => (clone $query)->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => (clone $query)->whereMonth('appointment_date', now()->month)->count(),
            'by_doctor' => (clone $query)->with('doctor')->get()->groupBy('doctor_id')->map(function ($items, $doctorId) {
                $doctor = $items->first()->doctor;
                return [
                    'doctor_name' => $doctor->name ?? 'Unknown',
                    'count' => $items->count(),
                ];
            })->sortByDesc('count')->take(5),
        ];
    }

    public function getRequestsProperty()
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->where('status', 'requested')
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('patient', function ($patientQuery) {
                    $patientQuery->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('middle_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('card_number', 'like', '%' . $this->search . '%');
                })->orWhereHas('doctor', function ($doctorQuery) {
                    $doctorQuery->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        }

        if ($this->visitType) {
            $query->where('visit_type', $this->visitType);
        }

        if ($this->dateFrom) {
            $query->whereDate('appointment_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('appointment_date', '<=', $this->dateTo);
        }

        return $query->paginate($this->perPage);
    }

    public function viewDetails($appointmentId)
    {
        $this->selectedAppointment = Appointment::with(['patient', 'doctor', 'creator'])
            ->findOrFail($appointmentId);
        $this->showDetailsModal = true;
    }

    public function openApproveModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::with(['patient', 'doctor'])
            ->findOrFail($appointmentId);
        
        $this->approveDate = $this->selectedAppointment->appointment_date->format('Y-m-d');
        $this->approveTime = $this->selectedAppointment->appointment_time->format('H:i');
        $this->approveDoctorId = $this->selectedAppointment->doctor_id;
        
        $this->loadAvailableSlots();
        $this->showApproveModal = true;
    }

    public function updatedApproveDate()
    {
        $this->loadAvailableSlots();
    }

    public function updatedApproveDoctorId()
    {
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        if ($this->approveDoctorId && $this->approveDate) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->approveDoctorId,
                $this->approveDate
            );
        }
    }

    public function selectSlot($slotTime)
    {
        $this->approveTime = $slotTime;
    }

    public function approve()
    {
        $this->validate([
            'approveDate' => 'required|date|after_or_equal:today',
            'approveTime' => 'required',
            'approveDoctorId' => 'required|exists:users,id',
        ]);

        try {
            // Update the appointment
            $this->selectedAppointment->update([
                'status' => 'scheduled',
                'appointment_date' => $this->approveDate,
                'appointment_time' => $this->approveTime,
                'doctor_id' => $this->approveDoctorId,
            ]);

            // Log history
            $this->appointmentService->logHistory(
                $this->selectedAppointment,
                auth()->id(),
                'approved',
                ['note' => 'Appointment request approved']
            );

            $this->reset(['showApproveModal', 'selectedAppointment', 'approveDate', 'approveTime', 'approveDoctorId', 'availableSlots']);
            $this->calculateStats();
            $this->dispatch('notify', 'Appointment request approved successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Approval failed: ' . $e->getMessage(), 'error');
        }
    }

    public function openRejectModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->showRejectModal = true;
    }

    public function reject()
    {
        $this->validate([
            'rejectionReason' => 'required|min:5',
        ]);

        try {
            $this->selectedAppointment->update([
                'status' => 'cancelled',
                'additional_notes' => $this->rejectionReason,
            ]);

            // Log history
            $this->appointmentService->logHistory(
                $this->selectedAppointment,
                auth()->id(),
                'rejected',
                ['reason' => $this->rejectionReason]
            );

            $this->reset(['showRejectModal', 'selectedAppointment', 'rejectionReason']);
            $this->calculateStats();
            $this->dispatch('notify', 'Appointment request rejected', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Rejection failed: ' . $e->getMessage(), 'error');
        }
    }

    public function openRescheduleModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->approveDate = $this->selectedAppointment->appointment_date->format('Y-m-d');
        $this->approveTime = $this->selectedAppointment->appointment_time->format('H:i');
        $this->approveDoctorId = $this->selectedAppointment->doctor_id;
        $this->loadAvailableSlots();
        $this->showRescheduleModal = true;
    }

    public function reschedule()
    {
        $this->validate([
            'approveDate' => 'required|date|after_or_equal:today',
            'approveTime' => 'required',
            'approveDoctorId' => 'required|exists:users,id',
            'rejectionReason' => 'required|min:5',
        ]);

        try {
            $this->selectedAppointment->update([
                'appointment_date' => $this->approveDate,
                'appointment_time' => $this->approveTime,
                'doctor_id' => $this->approveDoctorId,
                'reschedule_reason' => $this->rejectionReason,
            ]);

            // Log history
            $this->appointmentService->logHistory(
                $this->selectedAppointment,
                auth()->id(),
                'rescheduled',
                [
                    'reason' => $this->rejectionReason,
                    'new_date' => $this->approveDate,
                    'new_time' => $this->approveTime,
                ]
            );

            $this->reset(['showRescheduleModal', 'selectedAppointment', 'approveDate', 'approveTime', 'approveDoctorId', 'rejectionReason', 'availableSlots']);
            $this->calculateStats();
            $this->dispatch('notify', 'Appointment rescheduled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Reschedule failed: ' . $e->getMessage(), 'error');
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'doctorId', 'visitType']);
        $this->dateFrom = now()->format('Y-m-d');
        $this->dateTo = now()->addMonth()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.appointment.appointment-requests', [
            'requests' => $this->requests,
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'visitTypes' => [
                'consultation' => 'Consultation',
                'follow-up' => 'Follow-up',
                'lab_review' => 'Lab Review',
                'rehab_milestone' => 'Rehab Milestone',
                'emergency' => 'Emergency',
                'other' => 'Other',
            ],
        ]);
    }
}