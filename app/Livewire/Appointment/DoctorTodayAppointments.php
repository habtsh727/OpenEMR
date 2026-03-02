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
    
    // Modals
    public $showCheckInModal = false;
    public $showCompleteModal = false;
    public $showNotesModal = false;
    public $showRescheduleModal = false;
    public $showCancelModal = false;
    
    // Forms
    public $doctorNotes = '';
    public $newDate;
    public $newTime;
    public $rescheduleReason;
    public $cancellationReason;
    public $availableSlots = [];
    
    // Loading states
    public $confirmingAction = false;

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->newDate = Carbon::now('Africa/Addis_Ababa')->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
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
        
        return [
            'total' => Appointment::forToday()->count(),
            'scheduled' => Appointment::forToday()->where('status', 'scheduled')->count(),
            'completed' => Appointment::forToday()->where('status', 'completed')->count(),
            'missed' => Appointment::forToday()->where('status', 'missed')->count(),
            'checked_in' => Appointment::forToday()->whereNotNull('checked_in_at')->count(),
        ];
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
            
            $this->dispatch('notify', 'Patient checked in successfully!', 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Check-in failed: ' . $e->getMessage(), 'error');
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
            
            $this->dispatch('notify', 'Appointment completed successfully!', 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to complete: ' . $e->getMessage(), 'error');
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
            
            $this->dispatch('notify', 'Notes saved successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to save notes: ' . $e->getMessage(), 'error');
        }
    }

    // Reschedule
    public function openRescheduleModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
        $this->newDate = $this->selectedAppointment->appointment_date->format('Y-m-d');
        $this->newTime = $this->selectedAppointment->appointment_time->format('H:i');
        $this->showRescheduleModal = true;
        $this->loadAvailableSlots();
    }

    public function updatedNewDate()
    {
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        if ($this->newDate) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                auth()->id(),
                $this->newDate
            );
        }
    }

    public function selectSlot($slotTime)
    {
        $this->newTime = $slotTime;
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

            $this->reset(['showRescheduleModal', 'selectedAppointment', 'rescheduleReason', 'availableSlots']);
            $this->dispatch('notify', 'Appointment rescheduled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Reschedule failed: ' . $e->getMessage(), 'error');
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Cancel
    public function openCancelModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::findOrFail($appointmentId);
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
            $this->dispatch('notify', 'Appointment cancelled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Cancellation failed: ' . $e->getMessage(), 'error');
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
            $this->dispatch('notify', 'Appointment marked as missed', 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to mark as missed: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.appointment.doctor-today-appointments', [
            'appointments' => $this->todayAppointments,
            'stats' => $this->stats,
        ]);
    }
}