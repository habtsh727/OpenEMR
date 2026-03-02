<?php
// app/Livewire/Appointment/DoctorTodayAppointments.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class DoctorTodayAppointments extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $viewMode = 'list';
    public $selectedAppointment = null;
    public $timeSlots = [];
    
    // Modals
    public $showCheckInModal = false;
    public $showCompleteModal = false;
    public $showNotesModal = false;
    public $showRescheduleModal = false;
    public $showCancelModal = false;
    public $showTimelineModal = false;
    
    // Forms
    public $doctorNotes = '';
    public $newDate;
    public $newTime;
    public $rescheduleReason;
    public $cancellationReason;
    public $availableSlots = [];
    public $selectedSlot = null;
    
    // Loading states
    public $confirmingAction = false;
    public $isLoading = false;

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->newDate = Carbon::now('Africa/Addis_Ababa')->format('Y-m-d');
        $this->generateTimeSlots();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function generateTimeSlots()
    {
        $startTime = Carbon::parse('08:00', 'Africa/Addis_Ababa');
        $endTime = Carbon::parse('17:00', 'Africa/Addis_Ababa');
        
        while ($startTime <= $endTime) {
            $this->timeSlots[] = [
                'time' => $startTime->format('H:i'),
                'display' => $startTime->format('h:i A'),
                'is_past' => $startTime->isPast(),
            ];
            $startTime->addMinutes(30);
        }
    }

    public function getTodayAppointmentsProperty()
    {
        $query = Appointment::with(['patient'])
            ->forDoctor(auth()->id())
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

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->paginate(15);
    }

    public function getStatsProperty()
    {
        $today = Carbon::today('Africa/Addis_Ababa')->format('Y-m-d');
        
        $total = Appointment::forToday()->count();
        $scheduled = Appointment::forToday()->where('status', 'scheduled')->count();
        $checkedIn = Appointment::forToday()->whereNotNull('checked_in_at')->count();
        $completed = Appointment::forToday()->where('status', 'completed')->count();
        
        return [
            'total' => $total,
            'scheduled' => $scheduled,
            'checked_in' => $checkedIn,
            'completed' => $completed,
            'progress_percentage' => $total > 0 ? round(($checkedIn / $total) * 100, 1) : 0,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
        ];
    }

    public function getAppointmentsByTimeSlotProperty()
    {
        $appointments = $this->todayAppointments;
        $grouped = [];
        
        foreach ($this->timeSlots as $slot) {
            $slotAppointments = $appointments->filter(function ($appointment) use ($slot) {
                return $appointment->appointment_time->format('H:i') === $slot['time'];
            });
            
            $grouped[] = [
                'slot' => $slot,
                'appointments' => $slotAppointments,
                'count' => $slotAppointments->count(),
            ];
        }
        
        return collect($grouped);
    }

    // Check In
    public function openCheckInModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        
        if (!$this->selectedAppointment->isCheckInAvailable()) {
            $this->dispatch('notify', 'This appointment cannot be checked in.', 'error');
            return;
        }
        
        $this->showCheckInModal = true;
    }

    public function confirmCheckIn()
    {
        $this->confirmingAction = true;

        try {
            $this->appointmentService->checkIn(
                $this->selectedAppointment,
                auth()->id()
            );

            $this->showCheckInModal = false;
            $this->selectedAppointment = null;
            
            $this->dispatch('notify', [
                'message' => 'Patient checked in successfully!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Check-in failed: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Complete
    public function openCompleteModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->doctorNotes = $this->selectedAppointment->doctor_notes ?? '';
        $this->showCompleteModal = true;
    }

    public function confirmComplete()
    {
        $this->confirmingAction = true;

        try {
            $this->appointmentService->complete(
                $this->selectedAppointment,
                auth()->id(),
                $this->doctorNotes
            );

            $this->showCompleteModal = false;
            $this->selectedAppointment = null;
            $this->doctorNotes = '';
            
            $this->dispatch('notify', [
                'message' => 'Appointment completed successfully!',
                'type' => 'success'
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to complete: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Notes
    public function openNotesModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->doctorNotes = $this->selectedAppointment->doctor_notes ?? '';
        $this->showNotesModal = true;
    }

    public function saveNotes()
    {
        $this->validate([
            'doctorNotes' => 'nullable|string|max:5000',
        ]);

        try {
            $this->appointmentService->updateNotes(
                $this->selectedAppointment,
                auth()->id(),
                $this->doctorNotes
            );

            $this->showNotesModal = false;
            $this->selectedAppointment = null;
            $this->doctorNotes = '';
            
            $this->dispatch('notify', [
                'message' => 'Notes saved successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to save notes: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    // Reschedule
    public function openRescheduleModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->newDate = $this->selectedAppointment->appointment_date->format('Y-m-d');
        $this->newTime = $this->selectedAppointment->appointment_time->format('H:i');
        $this->selectedSlot = $this->newTime;
        $this->rescheduleReason = '';
        $this->showRescheduleModal = true;
        $this->loadAvailableSlots();
    }

    public function updatedNewDate()
    {
        $this->selectedSlot = null;
        $this->newTime = null;
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        $this->isLoading = true;
        
        if ($this->newDate) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                auth()->id(),
                $this->newDate
            );
        }
        
        $this->isLoading = false;
    }

    public function selectSlot($slotTime)
    {
        $this->newTime = $slotTime;
        $this->selectedSlot = $slotTime;
    }

    public function reschedule()
    {
        $this->validate([
            'newDate' => 'required|date|after_or_equal:today',
            'newTime' => 'required',
            'rescheduleReason' => 'required|min:5',
        ]);

        $this->confirmingAction = true;

        try {
            $this->appointmentService->reschedule(
                $this->selectedAppointment,
                auth()->id(),
                $this->newDate,
                $this->newTime,
                $this->rescheduleReason
            );

            $this->reset(['showRescheduleModal', 'selectedAppointment', 'rescheduleReason', 'availableSlots', 'newDate', 'newTime', 'selectedSlot']);
            $this->dispatch('notify', [
                'message' => 'Appointment rescheduled successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Reschedule failed: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Cancel
    public function openCancelModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->cancellationReason = '';
        $this->showCancelModal = true;
    }

    public function cancel()
    {
        $this->validate([
            'cancellationReason' => 'required|min:5',
        ]);

        $this->confirmingAction = true;

        try {
            $this->appointmentService->cancel(
                $this->selectedAppointment,
                auth()->id(),
                $this->cancellationReason
            );

            $this->reset(['showCancelModal', 'selectedAppointment', 'cancellationReason']);
            $this->dispatch('notify', [
                'message' => 'Appointment cancelled successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Cancellation failed: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Mark Missed
    public function markMissed($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        try {
            $this->appointmentService->markAsMissed($appointment, auth()->id());
            $this->dispatch('notify', [
                'message' => 'Appointment marked as missed',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to mark as missed: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.appointment.doctor-today-appointments', [
            'appointments' => $this->todayAppointments,
            'stats' => $this->stats,
            'appointmentsBySlot' => $this->appointmentsByTimeSlot,
            'timeSlots' => $this->timeSlots,
        ]);
    }
}