<?php
// app/Livewire/Appointment/CalendarView.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarView extends Component
{
    public $currentDate;
    public $view = 'month'; // month, week
    public $selectedDoctor = '';
    public $selectedDate = null;
    public $dayAppointments = [];
    public $showDayModal = false;
    public $selectedAppointment = null;
    public $showAppointmentModal = false;

    protected $listeners = ['refreshCalendar' => '$refresh'];

    public function mount()
    {
        $this->currentDate = Carbon::now('Africa/Addis_Ababa');
    }

    public function previous()
    {
        if ($this->view === 'month') {
            $this->currentDate = $this->currentDate->subMonth();
        } else {
            $this->currentDate = $this->currentDate->subWeek();
        }
    }

    public function next()
    {
        if ($this->view === 'month') {
            $this->currentDate = $this->currentDate->addMonth();
        } else {
            $this->currentDate = $this->currentDate->addWeek();
        }
    }

    public function goToToday()
    {
        $this->currentDate = Carbon::now('Africa/Addis_Ababa');
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function selectDate($date)
    {
        $this->selectedDate = Carbon::parse($date);
        $this->loadDayAppointments();
        $this->showDayModal = true;
    }

    public function selectAppointment($appointmentId)
    {
        $this->selectedAppointment = Appointment::with(['patient', 'doctor'])
            ->findOrFail($appointmentId);
        $this->showAppointmentModal = true;
    }

    public function loadDayAppointments()
    {
        if (!$this->selectedDate) {
            return;
        }

        $query = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', $this->selectedDate->toDateString());

        if ($this->selectedDoctor) {
            $query->where('doctor_id', $this->selectedDoctor);
        }

        $this->dayAppointments = $query->orderBy('appointment_time')->get();
    }

    // COMPUTED PROPERTIES - These will be accessible as $this->property in blade
    public function getMonthAppointmentsProperty()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();

        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->when($this->selectedDoctor, fn($q) => $q->where('doctor_id', $this->selectedDoctor))
            ->get();

        // Group by date manually to avoid collection issues
        $grouped = [];
        foreach ($appointments as $appointment) {
            $dateKey = $appointment->appointment_date->format('Y-m-d');
            if (!isset($grouped[$dateKey])) {
                $grouped[$dateKey] = [];
            }
            $grouped[$dateKey][] = $appointment;
        }

        // Convert arrays to collections
        foreach ($grouped as $key => $value) {
            $grouped[$key] = collect($value);
        }

        return $grouped;
    }

    public function getCalendarDaysProperty()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        $startOfWeek = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);
        
        $days = [];
        $today = Carbon::now('Africa/Addis_Ababa')->startOfDay();
        $monthAppointments = $this->monthAppointments; // This calls the computed property
        
        $current = $startOfWeek->copy();
        
        while ($current <= $endOfWeek) {
            $dateStr = $current->format('Y-m-d');
            $isCurrentMonth = $current->month === $this->currentDate->month;
            
            // Safely get appointments for this date
            $dayAppointments = isset($monthAppointments[$dateStr]) 
                ? $monthAppointments[$dateStr] 
                : collect();
            
            $statusCounts = [
                'scheduled' => 0,
                'completed' => 0,
                'missed' => 0,
                'cancelled' => 0,
                'rescheduled' => 0,
            ];
            
            // Count appointments by status
            foreach ($dayAppointments as $appointment) {
                if (isset($statusCounts[$appointment->status])) {
                    $statusCounts[$appointment->status]++;
                }
            }
            
            $days[] = [
                'date' => $current->copy(),
                'formatted' => $dateStr,
                'day' => $current->day,
                'day_name' => $current->format('D'),
                'month' => $current->format('M'),
                'is_current_month' => $isCurrentMonth,
                'is_today' => $current->startOfDay()->eq($today),
                'is_weekend' => $current->isWeekend(),
                'is_past' => $current->startOfDay()->lt($today),
                'appointments' => $dayAppointments,
                'total' => $dayAppointments->count(),
                'status_counts' => $statusCounts,
                'has_appointments' => $dayAppointments->isNotEmpty(),
            ];
            
            $current->addDay();
        }
        
        return $days;
    }

    public function getWeekDaysProperty()
    {
        $startOfWeek = $this->currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $this->currentDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        $days = [];
        $today = Carbon::now('Africa/Addis_Ababa')->startOfDay();
        
        for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            
            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_date', $dateStr)
                ->when($this->selectedDoctor, fn($q) => $q->where('doctor_id', $this->selectedDoctor))
                ->orderBy('appointment_time')
                ->get();
            
            $days[] = [
                'date' => $date->copy(),
                'formatted' => $dateStr,
                'day' => $date->day,
                'name' => $date->format('l'),
                'short_name' => $date->format('D'),
                'is_today' => $date->startOfDay()->eq($today),
                'is_weekend' => $date->isWeekend(),
                'appointments' => $appointments,
                'total' => $appointments->count(),
            ];
        }
        
        return $days;
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => 'bg-blue-500',
            'completed' => 'bg-green-500',
            'missed' => 'bg-red-500',
            'cancelled' => 'bg-gray-500',
            'rescheduled' => 'bg-yellow-500',
            default => 'bg-gray-400'
        };
    }

    public function render()
    {
        return view('livewire.appointment.calendar-view', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'calendarDays' => $this->calendarDays, // Pass as regular variable
            'weekDays' => $this->weekDays,
        ]);
    }
}