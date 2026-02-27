<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarView extends Component
{
    public $currentDate;
    public $view = 'month'; // month, week, day
    public $selectedDoctor = '';
    public $selectedDate = null;
    public $selectedAppointment = null;
    public $dayAppointments = [];
    public $showDayDetails = false;
    public $showAppointmentDetails = false;
    
    // Statistics
    public $stats = [];
    public $doctorStats = [];

    protected $listeners = ['refreshCalendar' => '$refresh'];

    public function mount()
    {
        $this->currentDate = now();
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_today' => Appointment::forToday()->count(),
            'total_week' => Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_month' => Appointment::whereMonth('appointment_date', now()->month)->count(),
            'completed' => Appointment::forToday()->where('status', 'completed')->count(),
            'scheduled' => Appointment::forToday()->where('status', 'scheduled')->count(),
            'missed' => Appointment::forToday()->where('status', 'missed')->count(),
        ];

        $this->doctorStats = Appointment::forToday()
            ->with('doctor')
            ->get()
            ->groupBy('doctor_id')
            ->map(function ($appointments, $doctorId) {
                $doctor = $appointments->first()->doctor;
                return [
                    'doctor_name' => $doctor->name ?? 'Unknown',
                    'total' => $appointments->count(),
                    'completed' => $appointments->where('status', 'completed')->count(),
                    'scheduled' => $appointments->where('status', 'scheduled')->count(),
                ];
            });
    }

    public function previous()
    {
        switch ($this->view) {
            case 'month':
                $this->currentDate = $this->currentDate->subMonth();
                break;
            case 'week':
                $this->currentDate = $this->currentDate->subWeek();
                break;
            case 'day':
                $this->currentDate = $this->currentDate->subDay();
                break;
        }
    }

    public function next()
    {
        switch ($this->view) {
            case 'month':
                $this->currentDate = $this->currentDate->addMonth();
                break;
            case 'week':
                $this->currentDate = $this->currentDate->addWeek();
                break;
            case 'day':
                $this->currentDate = $this->currentDate->addDay();
                break;
        }
    }

    public function goToToday()
    {
        $this->currentDate = now();
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function selectDate($date)
    {
        $this->selectedDate = Carbon::parse($date);
        $this->loadDayAppointments();
        $this->showDayDetails = true;
    }

    public function selectAppointment($appointmentId)
    {
        $this->selectedAppointment = Appointment::with(['patient', 'doctor'])
            ->findOrFail($appointmentId);
        $this->showAppointmentDetails = true;
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

    public function getMonthAppointmentsProperty()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();

        return Appointment::whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->when($this->selectedDoctor, fn($q) => $q->where('doctor_id', $this->selectedDoctor))
            ->get(['id', 'appointment_date', 'status', 'doctor_id', 'patient_id', 'appointment_time'])
            ->groupBy(function ($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            });
    }

    public function getCalendarDaysProperty()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        $startOfWeek = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);
        
        $days = [];
        $today = now()->startOfDay();
        
        // Generate all days from start of week to end of week
        $current = $startOfWeek->copy();
        
        while ($current <= $endOfWeek) {
            $dateStr = $current->format('Y-m-d');
            $isCurrentMonth = $current->month === $this->currentDate->month;
            $dayAppointments = $this->monthAppointments->get($dateStr, collect());
            
            // Categorize appointments by status
            $statusCounts = [
                'scheduled' => $dayAppointments->where('status', 'scheduled')->count(),
                'completed' => $dayAppointments->where('status', 'completed')->count(),
                'missed' => $dayAppointments->where('status', 'missed')->count(),
                'cancelled' => $dayAppointments->where('status', 'cancelled')->count(),
                'rescheduled' => $dayAppointments->where('status', 'rescheduled')->count(),
                'requested' => $dayAppointments->where('status', 'requested')->count(),
            ];
            
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
                'peak_hours' => $this->getPeakHour($dayAppointments),
            ];
            
            $current->addDay();
        }
        
        return $days;
    }

    private function getPeakHour($appointments)
    {
        if ($appointments->isEmpty()) {
            return null;
        }
        
        $hours = $appointments->groupBy(function ($appointment) {
            return $appointment->appointment_time->format('H');
        })->map->count();
        
        $peakHour = $hours->sortDesc()->keys()->first();
        
        if ($peakHour) {
            $hour = (int)$peakHour;
            $period = $hour < 12 ? 'AM' : 'PM';
            $displayHour = $hour > 12 ? $hour - 12 : ($hour == 0 ? 12 : $hour);
            return $displayHour . ':00 ' . $period;
        }
        
        return null;
    }

    public function getWeekDaysProperty()
    {
        $startOfWeek = $this->currentDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $this->currentDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        $days = [];
        $today = now()->startOfDay();
        
        for ($date = $startOfWeek->copy(); $date <= $endOfWeek; $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            
            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_date', $dateStr)
                ->when($this->selectedDoctor, fn($q) => $q->where('doctor_id', $this->selectedDoctor))
                ->orderBy('appointment_time')
                ->get()
                ->groupBy(function ($appointment) {
                    return $appointment->appointment_time->format('H') . ':00';
                });
            
            $days[] = [
                'date' => $date->copy(),
                'formatted' => $dateStr,
                'day' => $date->day,
                'name' => $date->format('l'),
                'short_name' => $date->format('D'),
                'is_today' => $date->startOfDay()->eq($today),
                'is_weekend' => $date->isWeekend(),
                'appointments_by_hour' => $appointments,
                'total' => $appointments->flatten()->count(),
            ];
        }
        
        return $days;
    }

    public function getHourSlotsProperty()
    {
        $slots = [];
        $start = Carbon::parse('08:00');
        $end = Carbon::parse('17:00');
        
        while ($start <= $end) {
            $slots[] = [
                'time' => $start->format('H:i'),
                'display' => $start->format('h:i A'),
                'hour' => $start->format('H'),
            ];
            $start->addMinutes(60);
        }
        
        return $slots;
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => 'bg-blue-500',
            'completed' => 'bg-green-500',
            'missed' => 'bg-red-500',
            'cancelled' => 'bg-gray-500',
            'rescheduled' => 'bg-yellow-500',
            'requested' => 'bg-purple-500',
            default => 'bg-gray-400'
        };
    }

    public function getStatusBadgeColor($status)
    {
        return match($status) {
            'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'missed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'cancelled' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            'rescheduled' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'requested' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function render()
    {
        return view('livewire.appointment.calendar-view', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'calendarDays' => $this->calendarDays,
            'weekDays' => $this->weekDays,
            'hourSlots' => $this->hourSlots,
        ]);
    }
}