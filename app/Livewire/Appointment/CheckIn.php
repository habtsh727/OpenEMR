<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\Encounter;
use App\Services\AppointmentService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CheckIn extends Component
{
    public $appointmentId;
    public $appointment = null;
    public $showModal = false;
    public $isProcessing = false;
    public $encounter = null;
    public $showSuccess = false;

    protected $appointmentService;

    protected $listeners = ['openCheckInModal' => 'loadAppointment'];

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function loadAppointment($appointmentId)
    {
        $this->appointmentId = $appointmentId;
        $this->appointment = Appointment::with(['patient', 'doctor'])
            ->findOrFail($appointmentId);
        
        if (!$this->appointment->isCheckInAvailable()) {
            $this->dispatch('notify', 'This appointment cannot be checked in.', 'error');
            return;
        }

        $this->showModal = true;
        $this->showSuccess = false;
        $this->encounter = null;
    }

    public function processCheckIn()
    {
        $this->isProcessing = true;

        try {
            $this->encounter = $this->appointmentService->checkIn(
                $this->appointment,
                Auth::id()
            );

            $this->showSuccess = true;
            
            // Dispatch events for parent components
            $this->dispatch('checkInCompleted', encounterId: $this->encounter->id);
            $this->dispatch('notify', 'Patient checked in successfully!', 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 'Check-in failed: ' . $e->getMessage(), 'error');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function closeModal()
    {
        $this->reset(['appointmentId', 'appointment', 'showModal', 'isProcessing', 'encounter', 'showSuccess']);
    }

    public function render()
    {
        return view('livewire.appointment.check-in');
    }
}