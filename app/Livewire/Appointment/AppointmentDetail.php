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

    // Modals
    public $showCheckInModal = false;
    public $showCompleteModal = false;
    public $showRescheduleModal = false;
    public $showCancelModal = false;
    public $confirmingAction = false;

    // Reschedule form
    public $newDate;
    public $newTime;
    public $rescheduleReason;
    public $availableSlots = [];

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
    }

    public function getTimeUntilAttribute()
    {
        $appointmentDateTime = Carbon::parse(
            $this->appointment->appointment_date->format('Y-m-d') . ' ' . 
            $this->appointment->appointment_time->format('H:i:s'),
            'Africa/Addis_Ababa'
        );

        if ($appointmentDateTime->isPast()) {
            return 'Past';
        }

        $diff = now()->diff($appointmentDateTime);

        if ($diff->days > 0) {
            return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' from now';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' from now';
        } elseif ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' from now';
        }

        return 'Soon';
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
            $this->dispatch('notify', 'Notes saved successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to save notes: ' . $e->getMessage(), 'error');
        }
    }

    // Check In Methods
    public function openCheckInModal()
    {
        if (!$this->appointment->isCheckInAvailable()) {
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
                $this->appointment,
                auth()->id()
            );

            $this->appointment->refresh();
            $this->showCheckInModal = false;
            $this->dispatch('notify', 'Patient checked in successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Check-in failed: ' . $e->getMessage(), 'error');
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
            $this->showCompleteModal = false;
            $this->dispatch('notify', 'Appointment completed successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to complete: ' . $e->getMessage(), 'error');
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Reschedule Methods
    public function openRescheduleModal()
    {
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
                $this->appointment->doctor_id,
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
                $this->appointment,
                auth()->id(),
                $this->newDate,
                $this->newTime,
                $this->rescheduleReason
            );

            $this->appointment->refresh();
            $this->showRescheduleModal = false;
            $this->dispatch('notify', 'Appointment rescheduled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Reschedule failed: ' . $e->getMessage(), 'error');
        } finally {
            $this->confirmingAction = false;
        }
    }

    // Cancel Methods
    public function openCancelModal()
    {
        $this->showCancelModal = true;
    }

    public function cancel()
    {
        $this->validate([
            'rescheduleReason' => 'required|min:5',
        ]);

        $this->confirmingAction = true;

        try {
            $this->appointmentService->cancel(
                $this->appointment,
                auth()->id(),
                $this->rescheduleReason
            );

            $this->appointment->refresh();
            $this->showCancelModal = false;
            $this->dispatch('notify', 'Appointment cancelled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Cancellation failed: ' . $e->getMessage(), 'error');
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
            $this->dispatch('notify', 'Appointment marked as missed', 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to mark as missed: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.appointment.appointment-detail', [
            'histories' => $this->appointment->histories()->latest()->take(20)->get(),
        ]);
    }
}