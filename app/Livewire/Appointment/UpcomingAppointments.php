<?php
// app/Livewire/Appointment/UpcomingAppointments.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class UpcomingAppointments extends Component
{
    use WithPagination;

    public $search = '';
    public $doctorId = '';
    public $status = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;
    public $highlightNext48 = true;

    // Modals
    public $showRescheduleModal = false;
    public $showCancelModal = false;
    public $selectedAppointment = null;

    // Reschedule form
    public $newDate;
    public $newTime;
    public $rescheduleReason;
    public $cancellationReason;
    public $availableSlots = [];
    public $selectedSlot = null;

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function mount()
    {
        $this->dateFrom = Carbon::now('Africa/Addis_Ababa')->format('Y-m-d');
        $this->dateTo = Carbon::now('Africa/Addis_Ababa')->addMonth()->format('Y-m-d');
        $this->newDate = Carbon::now('Africa/Addis_Ababa')->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDoctorId()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function getUpcomingAppointmentsProperty()
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->whereDate('appointment_date', '>=', Carbon::now('Africa/Addis_Ababa')->format('Y-m-d'))
            ->orderBy('appointment_date')
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

        if ($this->dateFrom) {
            $query->whereDate('appointment_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('appointment_date', '<=', $this->dateTo);
        }

        return $query->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $now = Carbon::now('Africa/Addis_Ababa');
        $next48 = $now->copy()->addHours(48);

        return [
            'total_upcoming' => Appointment::whereIn('status', ['scheduled', 'rescheduled'])
                ->whereDate('appointment_date', '>=', $now->format('Y-m-d'))
                ->count(),
            'next_48_hours' => Appointment::whereIn('status', ['scheduled', 'rescheduled'])
                ->whereBetween('appointment_date', [$now->format('Y-m-d'), $next48->format('Y-m-d')])
                ->count(),
            'this_week' => Appointment::whereIn('status', ['scheduled', 'rescheduled'])
                ->whereBetween('appointment_date', [$now->startOfWeek()->format('Y-m-d'), $now->endOfWeek()->format('Y-m-d')])
                ->count(),
            'this_month' => Appointment::whereIn('status', ['scheduled', 'rescheduled'])
                ->whereMonth('appointment_date', $now->month)
                ->count(),
        ];
    }

    public function isNext48Hours($date)
    {
        $now = Carbon::now('Africa/Addis_Ababa');
        $next48 = $now->copy()->addHours(48);
        $appointmentDate = Carbon::parse($date);
        
        return $appointmentDate->between($now, $next48);
    }

    // Reschedule Methods
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
        if ($this->newDate && $this->selectedAppointment) {
            $this->availableSlots = $this->appointmentService->generateTimeSlots(
                $this->selectedAppointment->doctor_id,
                $this->newDate
            );
        }
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

        try {
            $this->appointmentService->reschedule(
                $this->selectedAppointment,
                auth()->id(),
                $this->newDate,
                $this->newTime,
                $this->rescheduleReason
            );

            $this->reset(['showRescheduleModal', 'selectedAppointment', 'rescheduleReason', 'availableSlots', 'newDate', 'newTime', 'selectedSlot']);
            $this->dispatch('notify', 'Appointment rescheduled successfully!', 'success');

        } catch (\Exception $e) {
            $this->dispatch('notify', 'Reschedule failed: ' . $e->getMessage(), 'error');
        }
    }

    // Cancel Methods
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
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'doctorId', 'status']);
        $this->dateFrom = Carbon::now('Africa/Addis_Ababa')->format('Y-m-d');
        $this->dateTo = Carbon::now('Africa/Addis_Ababa')->addMonth()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.appointment.upcoming-appointments', [
            'appointments' => $this->upcomingAppointments,
            'stats' => $this->stats,
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
        ]);
    }
}