<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Services\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TodayAppointments extends Component
{
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

    public function checkIn($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        if (!$appointment->isCheckInAvailable()) {
            $this->dispatch('notify', 'Cannot check in this appointment', 'error');
            return;
        }

        try {
            $encounter = $this->appointmentService->checkIn($appointment, Auth::id());
            
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
                Auth::id()
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
            $this->appointmentService->cancel($this->selectedAppointment, $reason, Auth::id());
            
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
            $this->appointmentService->markAsMissed($appointment, Auth::id());
            $this->dispatch('notify', 'Appointment marked as missed', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Failed to mark as missed: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->forToday()
            ->orderBy('appointment_time')
            ->get();

        return view('livewire.appointment.today-appointments', [
            'appointments' => $appointments,
        ]);
    }
}