<?php

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
    public $visitType = '';
    public $groupBy = 'date'; // date, doctor, type
    public $highlightNext48 = true;
    public $perPage = 20;

    protected $appointmentService;

    public function boot(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDoctorId()
    {
        $this->resetPage();
    }

    public function updatedVisitType()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'doctorId', 'visitType']);
    }

    public function getUpcomingAppointmentsProperty()
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->upcoming() // Using scope from Appointment model
            ->whereDate('appointment_date', '>=', now()->toDateString())
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

        if ($this->visitType) {
            $query->where('visit_type', $this->visitType);
        }

        return $query->paginate($this->perPage);
    }

    public function getGroupedAppointmentsProperty()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->upcoming()
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        if ($this->groupBy === 'date') {
            return $appointments->groupBy(function ($appointment) {
                return $appointment->appointment_date->format('Y-m-d');
            })->map(function ($group, $date) {
                return [
                    'date' => Carbon::parse($date),
                    'formatted_date' => Carbon::parse($date)->format('l, F j, Y'),
                    'is_today' => Carbon::parse($date)->isToday(),
                    'is_tomorrow' => Carbon::parse($date)->isTomorrow(),
                    'in_next_48' => Carbon::parse($date)->between(now(), now()->addHours(48)),
                    'appointments' => $group,
                    'count' => $group->count(),
                ];
            })->sortKeys();
        }

        if ($this->groupBy === 'doctor') {
            return $appointments->groupBy('doctor_id')->map(function ($group, $doctorId) {
                $doctor = $group->first()->doctor;
                return [
                    'doctor_id' => $doctorId,
                    'doctor_name' => $doctor->name,
                    'appointments' => $group,
                    'count' => $group->count(),
                    'first_appointment' => $group->min('appointment_date'),
                    'last_appointment' => $group->max('appointment_date'),
                ];
            });
        }

        if ($this->groupBy === 'type') {
            return $appointments->groupBy('visit_type')->map(function ($group, $type) {
                return [
                    'type' => $type,
                    'type_label' => ucfirst(str_replace('-', ' ', $type)),
                    'appointments' => $group,
                    'count' => $group->count(),
                ];
            });
        }

        return collect();
    }

    public function getStatsProperty()
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $next48 = now()->addHours(48);

        return [
            'total_upcoming' => Appointment::upcoming()->count(),
            'today' => Appointment::forToday()->whereIn('status', ['scheduled', 'rescheduled'])->count(),
            'tomorrow' => Appointment::whereDate('appointment_date', $tomorrow)
                ->whereIn('status', ['scheduled', 'rescheduled'])
                ->count(),
            'next_48_hours' => Appointment::whereBetween('appointment_date', [now()->toDateString(), $next48])
                ->whereIn('status', ['scheduled', 'rescheduled'])
                ->count(),
            'by_doctor' => Appointment::upcoming()
                ->get()
                ->groupBy('doctor_id')
                ->map(fn($g) => $g->count()),
        ];
    }

    public function reschedule($appointmentId)
    {
        $this->dispatch('openRescheduleModal', appointmentId: $appointmentId);
    }

    public function cancel($appointmentId)
    {
        $this->dispatch('openCancelModal', appointmentId: $appointmentId);
    }

    public function checkIn($appointmentId)
    {
        $this->dispatch('openCheckInModal', appointmentId: $appointmentId);
    }

    public function render()
    {
        return view('livewire.appointment.upcoming-appointments', [
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'visitTypes' => [
                'consultation' => 'Consultation',
                'follow-up' => 'Follow-up',
                'lab_review' => 'Lab Review',
                'rehab_milestone' => 'Rehab Milestone',
                'emergency' => 'Emergency',
                'other' => 'Other',
            ],
            'stats' => $this->stats,
            'groupedAppointments' => $this->groupedAppointments,
            'appointments' => $this->upcomingAppointments, // For non-grouped view
        ]);
    }
}