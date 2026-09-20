<?php
// app/Livewire/Appointment/AppointmentDetail.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Livewire\Component;
use Carbon\Carbon;

class AppointmentDetail extends Component
{
    public Appointment $appointment;
    public $activeTab = 'overview';
    public $doctorNotes = '';
    public $showEditNotes = false;
    public $timeUntil = '';

    // Modals
    public $showCheckInModal = false;
    public $showCompleteModal = false;
    public $showRescheduleModal = false;
    public $showCancelModal = false;
    public $confirmingAction = false;

    // Reschedule form
    public $newDate;
    public $newTime;
    public $selectedSlot = null;
    public $rescheduleReason;
    public $cancellationReason;
    public $availableSlots = [];
    public $loadingSlots = false;

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount($id)
    {
        $this->appointment = Appointment::with([
            'patient',
            'doctor',
            'creator',
            'histories.user'
        ])->findOrFail($id);

        $this->doctorNotes = $this->appointment->doctor_notes ?? '';
        $this->newDate = $this->appointment->appointment_date->format('Y-m-d');
        $this->newTime = $this->appointment->appointment_time->format('H:i');
        $this->selectedSlot = $this->newTime;
        $this->calculateTimeUntil();
    }

    public function calculateTimeUntil()
    {
        $appointmentDateTime = Carbon::parse(
            $this->appointment->appointment_date->format('Y-m-d') . ' ' . 
            $this->appointment->appointment_time->format('H:i:s'),
            'Africa/Addis_Ababa'
        );

        if ($appointmentDateTime->isPast()) {
            $this->timeUntil = 'Past';
            return;
        }

        $diff = now()->diff($appointmentDateTime);

        if ($diff->days > 0) {
            $this->timeUntil = $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' from now';
        } elseif ($diff->h > 0) {
            $this->timeUntil = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' from now';
        } elseif ($diff->i > 0) {
            $this->timeUntil = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' from now';
        } else {
            $this->timeUntil = 'Soon';
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // Notes Methods
    public function toggleEditNotes()
    {
        $this->showEditNotes = !$this->showEditNotes;
        $this->doctorNotes = $this->appointment->doctor_notes ?? '';
    }

    public function saveNotes()
    {
        $this->validate([
            'doctorNotes' => 'nullable|string|max:5000',
        ]);

        try {
            $this->appointmentService->updateNotes(
                $this->appointment,
                auth()->id(),
                $this->doctorNotes
            );

            $this->appointment->refresh();
            $this->showEditNotes = false;
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

    // Check In Methods
    public function openCheckInModal()
    {
        if (!$this->appointment->isCheckInAvailable()) {
            $this->dispatch('notify', [
                'message' => 'This appointment cannot be checked in.',
                'type' => 'error'
            ]);
            return;
        }
        $this->showCheckInModal = true;
    }

    public function confirmCheckIn()
    {
        $this->confirmingAction = true;

        try {
            $this->appointmentService->checkIn(
                $this->appointment,
                auth()->id()
            );

            $this->appointment->refresh();
            $this->calculateTimeUntil();
            $this->showCheckInModal = false;
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

    // Complete Methods
    public function openCompleteModal()
    {
        $this->showCompleteModal = true;
    }

    public function confirmComplete()
    {
        $this->confirmingAction = true;

        try {
            $this->appointmentService->complete(
                $this->appointment,
                auth()->id(),
                $this->doctorNotes
            );

            $this->appointment->refresh();
            $this->calculateTimeUntil();
            $this->showCompleteModal = false;
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

    // Reschedule Methods
    public function openRescheduleModal()
    {
        $this->showRescheduleModal = true;
        $this->rescheduleReason = '';
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
        $this->loadingSlots = true;
        
        if ($this->newDate) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->appointment->doctor_id,
                $this->newDate
            );
        }
        
        $this->loadingSlots = false;
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
                $this->appointment,
                auth()->id(),
                $this->newDate,
                $this->newTime,
                $this->rescheduleReason
            );

            $this->appointment->refresh();
            $this->calculateTimeUntil();
            $this->showRescheduleModal = false;
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

    // Cancel Methods
    public function openCancelModal()
    {
        $this->showCancelModal = true;
        $this->cancellationReason = '';
    }

    public function cancel()
    {
        $this->validate([
            'cancellationReason' => 'required|min:5',
        ]);

        $this->confirmingAction = true;

        try {
            $this->appointmentService->cancel(
                $this->appointment,
                auth()->id(),
                $this->cancellationReason
            );

            $this->appointment->refresh();
            $this->calculateTimeUntil();
            $this->showCancelModal = false;
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
    public function markMissed()
    {
        try {
            $this->appointmentService->markAsMissed($this->appointment, auth()->id());
            $this->appointment->refresh();
            $this->calculateTimeUntil();
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
        return view('livewire.appointment.appointment-detail', [
            'histories' => $this->appointment->histories()->latest()->take(20)->get(),
        ]);
    }
}