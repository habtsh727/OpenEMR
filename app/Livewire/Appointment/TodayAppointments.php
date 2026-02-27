<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Livewire\Component;
use Carbon\Carbon;

class TodayAppointments extends Component
{
    public $search = '';
    public $doctorId = '';
    public $status = '';
    public $viewMode = 'timeline'; // timeline, list, grid
    public $selectedAppointment = null;
    public $showCheckInModal = false;
    public $showRescheduleModal = false;
    public $showCancelModal = false;
    
    // Reschedule form
    public $newDate;
    public $newTime;
    public $newDoctorId;
    public $rescheduleReason;
    public $availableSlots = [];

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->newDate = now()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getTodayAppointmentsProperty()
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->forToday()
            ->orderBy('appointment_time');

        if ($this->search) {
            $query->whereHas('patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('middle_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('card_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function getStatsProperty()
    {
        $appointments = $this->todayAppointments;

        return [
            'total' => $appointments->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'missed' => $appointments->where('status', 'missed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'rescheduled' => $appointments->where('status', 'rescheduled')->count(),
            'checked_in' => $appointments->whereNotNull('checked_in_at')->count(),
            'pending_checkin' => $appointments->where('status', 'scheduled')->whereNull('checked_in_at')->count(),
        ];
    }

    public function getTimelineSlotsProperty()
    {
        $appointments = $this->todayAppointments;
        $slots = [];
        
        $startTime = Carbon::parse('08:00');
        $endTime = Carbon::parse('17:00');
        
        while ($startTime <= $endTime) {
            $slotTime = $startTime->format('H:i');
            $slotAppointments = $appointments->filter(function ($appointment) use ($slotTime) {
                return $appointment->appointment_time->format('H:i') === $slotTime;
            });
            
            $slots[] = [
                'time' => $startTime->format('h:i A'),
                'time_value' => $slotTime,
                'appointments' => $slotAppointments,
                'count' => $slotAppointments->count(),
                'has_appointments' => $slotAppointments->isNotEmpty(),
                'is_past' => $startTime->isPast(),
                'is_current' => $startTime->format('H:i') === now()->format('H:i'),
            ];
            
            $startTime->addMinutes(30);
        }
        
        return $slots;
    }

    public function checkIn($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        if (!$appointment->isCheckInAvailable()) {
            $this->dispatch('notify', 'Cannot check in this appointment', 'error');
            return;
        }

        try {
            $encounter = $this->appointmentService->checkIn($appointment, auth()->id());
            
            $this->dispatch('notify', 'Patient checked in successfully! Encounter #' . $encounter->id, 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Check-in failed: ' . $e->getMessage(), 'error');
        }
    }

    public function openRescheduleModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->newDate = $this->selectedAppointment->appointment_date->format('Y-m-d');
        $this->newTime = $this->selectedAppointment->appointment_time->format('H:i');
        $this->newDoctorId = $this->selectedAppointment->doctor_id;
        $this->showRescheduleModal = true;
        
        $this->loadAvailableSlots();
    }

    public function updatedNewDate()
    {
        $this->loadAvailableSlots();
    }

    public function updatedNewDoctorId()
    {
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        if ($this->newDate && $this->newDoctorId) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->newDoctorId,
                $this->newDate
            );
        }
    }

    public function reschedule()
    {
        $this->validate([
            'newDate' => 'required|date|after_or_equal:today',
            'newTime' => 'required',
            'newDoctorId' => 'required|exists:users,id',
            'rescheduleReason' => 'required|min:5',
        ]);

        try {
            $this->appointmentService->reschedule(
                $this->selectedAppointment,
                [
                    'appointment_date' => $this->newDate,
                    'appointment_time' => $this->newTime,
                    'doctor_id' => $this->newDoctorId,
                ],
                $this->rescheduleReason,
                auth()->id()
            );

            $this->reset(['showRescheduleModal', 'selectedAppointment', 'newDate', 'newTime', 'newDoctorId', 'rescheduleReason']);
            $this->dispatch('notify', 'Appointment rescheduled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Reschedule failed: ' . $e->getMessage(), 'error');
        }
    }

    public function openCancelModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->showCancelModal = true;
    }

    public function cancel($reason)
    {
        try {
            $this->appointmentService->cancel($this->selectedAppointment, $reason, auth()->id());
            
            $this->reset(['showCancelModal', 'selectedAppointment']);
            $this->dispatch('notify', 'Appointment cancelled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Cancellation failed: ' . $e->getMessage(), 'error');
        }
    }

    public function markMissed($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        try {
            $this->appointmentService->markAsMissed($appointment, auth()->id());
            $this->dispatch('notify', 'Appointment marked as missed', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to mark as missed: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.appointment.today-appointments', [
            'appointments' => $this->todayAppointments,
            'stats' => $this->stats,
            'timelineSlots' => $this->timelineSlots,
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'statuses' => ['scheduled', 'completed', 'missed', 'cancelled', 'rescheduled'],
        ]);
    }
}