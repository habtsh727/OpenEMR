<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class CalendarView extends Component
{
    public $currentDate;
    public $view = 'month'; // month, week
    public $selectedDoctor = '';
    public $selectedDate = null;
    public $dayAppointments = [];

    protected $listeners = ['dateSelected' => 'loadDayAppointments'];

    public function mount()
    {
        $this->currentDate = now();
    }

    public function previousMonth()
    {
        $this->currentDate = $this->currentDate->subMonth();
    }

    public function nextMonth()
    {
        $this->currentDate = $this->currentDate->addMonth();
    }

    public function previousWeek()
    {
        $this->currentDate = $this->currentDate->subWeek();
    }

    public function nextWeek()
    {
        $this->currentDate = $this->currentDate->addWeek();
    }

    public function goToToday()
    {
        $this->currentDate = now();
    }

    public function loadDayAppointments($date)
    {
        $this->selectedDate = $date;
        
        $query = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', $date);

        if ($this->selectedDoctor) {
            $query->where('doctor_id', $this->selectedDoctor);
        }

        $this->dayAppointments = $query->orderBy('appointment_time')->get();
    }

    public function getMonthAppointmentsProperty()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();

        $appointments = Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->when($this->selectedDoctor, fn($q) => $q->where('doctor_id', $this->selectedDoctor))
            ->get(['id', 'appointment_date', 'status', 'doctor_id']);

        // Group by date for easy access
        return $appointments->groupBy(function ($appointment) {
            return $appointment->appointment_date->format('Y-m-d');
        });
    }

    public function getCalendarDaysProperty()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        
        $days = [];
        
        // Add empty cells for days before month start
        for ($i = 0; $i < $startOfMonth->dayOfWeek; $i++) {
            $days[] = ['empty' => true];
        }
        
        // Add days of month
        for ($date = $startOfMonth->copy(); $date <= $endOfMonth; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $dayAppointments = $this->monthAppointments->get($dateStr, collect());
            
            $days[] = [
                'date' => $date->copy(),
                'formatted' => $dateStr,
                'day' => $date->day,
                'isToday' => $date->isToday(),
                'isWeekend' => $date->isWeekend(),
                'appointments' => $dayAppointments,
                'count' => $dayAppointments->count(),
                'statuses' => $dayAppointments->pluck('status')->unique(),
            ];
        }
        
        return $days;
    }

    public function getWeekDaysProperty()
    {
        $startOfWeek = $this->currentDate->copy()->startOfWeek();
        $endOfWeek = $this->currentDate->copy()->endOfWeek();
        
        $days = [];
        
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
                'isToday' => $date->isToday(),
                'appointments' => $appointments,
            ];
        }
        
        return $days;
    }

    public function render()
    {
        return view('livewire.appointment.calendar-view', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
        ]);
    }
}