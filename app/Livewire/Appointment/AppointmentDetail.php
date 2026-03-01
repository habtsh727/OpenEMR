<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\AppointmentHistory;
use App\Services\AppointmentService;
use Livewire\Component;
use Carbon\Carbon;

class AppointmentDetail extends Component
{
    public Appointment $appointment;
    public $activeTab = 'overview';
    public $showCancelModal = false;
    public $showRescheduleModal = false;
    public $showCheckInModal = false; // ADD THIS - was commented out
    public $cancellationReason = '';
    public $confirmingCheckIn = false; // ADD THIS for loading state

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

    public $timeUntil;

    public function mount($id)
    {
        $this->appointment = Appointment::with([
            'patient',
            'doctor',
            'creator',
            'encounter',
            'histories.user'
        ])->findOrFail($id);

        $this->newDate = $this->appointment->appointment_date->format('Y-m-d');
        $this->newTime = $this->appointment->appointment_time->format('H:i');
        $this->newDoctorId = $this->appointment->doctor_id;
        
        $this->timeUntil = $this->getTimeUntil();
    }

    public function getTimeUntil()
    {
        $appointmentDateTime = Carbon::parse(
            $this->appointment->appointment_date->format('Y-m-d') . ' ' . 
            $this->appointment->appointment_time->format('H:i:s')
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

    // SIMPLE CHECK-IN METHODS - FIXED like TodayAppointments
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
        $this->confirmingCheckIn = true;

        try {
            // SIMPLE UPDATE - just change status to completed, no encounter
            $this->appointment->update([
                'status' => 'completed',
                'checked_in_at' => now(),
            ]);

            // Log to history (optional)
            $this->appointment->histories()->create([
                'user_id' => auth()->id(),
                'action' => 'checked_in',
                'new_values' => ['status' => 'completed'],
            ]);

            $this->appointment->refresh();
            $this->showCheckInModal = false;

            $this->dispatch('notify', 'Patient checked in successfully!', 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Check-in failed: ' . $e->getMessage(), 'error');
        } finally {
            $this->confirmingCheckIn = false;
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

    public function updatedNewDoctorId()
    {
        $this->loadAvailableSlots();
    }

    protected function loadAvailableSlots()
    {
        if ($this->newDoctorId && $this->newDate) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->newDoctorId,
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
            'newDoctorId' => 'required|exists:users,id',
            'rescheduleReason' => 'required|min:5',
        ]);

        try {
            $this->appointmentService->reschedule(
                $this->appointment,
                [
                    'appointment_date' => $this->newDate,
                    'appointment_time' => $this->newTime,
                    'doctor_id' => $this->newDoctorId,
                ],
                $this->rescheduleReason,
                auth()->id()
            );

            $this->appointment->refresh();
            $this->showRescheduleModal = false;
            $this->reset(['rescheduleReason']);

            $this->dispatch('notify', 'Appointment rescheduled successfully!', 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Reschedule failed: ' . $e->getMessage(), 'error');
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
            'cancellationReason' => 'required|min:5',
        ]);

        try {
            $this->appointmentService->cancel(
                $this->appointment,
                $this->cancellationReason,
                auth()->id()
            );

            $this->appointment->refresh();
            $this->showCancelModal = false;

            $this->dispatch('notify', 'Appointment cancelled successfully!', 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Cancellation failed: ' . $e->getMessage(), 'error');
        }
    }

    // Mark as Missed
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

    public function getTimeUntilAttribute()
    {
        $appointmentDateTime = Carbon::parse(
            $this->appointment->appointment_date->format('Y-m-d') . ' ' .
                $this->appointment->appointment_time->format('H:i:s')
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

    public function getStatusColorAttribute()
    {
        return $this->appointment->status_color;
    }

    public function render()
    {
        return view('livewire.appointment.appointment-detail', [
            'histories' => $this->appointment->histories()->latest()->take(10)->get(),
        ]);
    }
}