<?php
// app/Livewire/Appointment/AllAppointments.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Carbon\Carbon;

class AllAppointments extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $doctorId = '';

    #[Url(history: true)]
    public $status = '';

    #[Url(history: true)]
    public $visitType = '';

    #[Url(history: true)]
    public $dateFrom = '';

    #[Url(history: true)]
    public $dateTo = '';

    #[Url(history: true)]
    public $sortField = 'appointment_date';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    public $perPage = 15;
    public $showFilters = false;
    public $datePreset = 'custom';

    protected $queryString = ['search', 'doctorId', 'status', 'visitType', 'dateFrom', 'dateTo', 'sortField', 'sortDirection'];

    public function mount()
    {
        $this->dateFrom = Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d');
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

    public function updatedVisitType()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->datePreset = 'custom';
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->datePreset = 'custom';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function setDatePreset($preset)
    {
        $this->datePreset = $preset;
        $now = Carbon::now('Africa/Addis_Ababa');

        switch ($preset) {
            case 'today':
                $this->dateFrom = $now->format('Y-m-d');
                $this->dateTo = $now->format('Y-m-d');
                break;
            case 'yesterday':
                $yesterday = $now->subDay();
                $this->dateFrom = $yesterday->format('Y-m-d');
                $this->dateTo = $yesterday->format('Y-m-d');
                break;
            case 'this_week':
                $this->dateFrom = $now->startOfWeek()->format('Y-m-d');
                $this->dateTo = $now->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $lastWeek = $now->subWeek();
                $this->dateFrom = $lastWeek->startOfWeek()->format('Y-m-d');
                $this->dateTo = $lastWeek->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $this->dateFrom = $now->startOfMonth()->format('Y-m-d');
                $this->dateTo = $now->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $this->dateFrom = $lastMonth->startOfMonth()->format('Y-m-d');
                $this->dateTo = $lastMonth->endOfMonth()->format('Y-m-d');
                break;
            case 'this_year':
                $this->dateFrom = $now->startOfYear()->format('Y-m-d');
                $this->dateTo = $now->endOfYear()->format('Y-m-d');
                break;
        }
        
        $this->resetPage();
    }

    public function getAppointmentsProperty()
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo]);

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

        if ($this->visitType) {
            $query->where('visit_type', $this->visitType);
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        $query = Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo]);

        if ($this->doctorId) {
            $query->where('doctor_id', $this->doctorId);
        }

        return [
            'total' => (clone $query)->count(),
            'scheduled' => (clone $query)->where('status', 'scheduled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'missed' => (clone $query)->where('status', 'missed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'rescheduled' => (clone $query)->where('status', 'rescheduled')->count(),
            'checked_in' => (clone $query)->whereNotNull('checked_in_at')->count(),
        ];
    }

    public function resetFilters()
    {
        $this->reset(['search', 'doctorId', 'status', 'visitType']);
        $this->dateFrom = Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d');
        $this->datePreset = 'custom';
    }

    public function exportToCsv()
    {
        $fileName = 'all_appointments_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Date', 'Time', 'Patient Name', 'Patient Card', 'Doctor', 'Visit Type', 'Status', 'Checked In', 'Notes']);

            // Data
            $appointments = Appointment::with(['patient', 'doctor'])
                ->whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
                ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId))
                ->when($this->status, fn($q) => $q->where('status', $this->status))
                ->when($this->visitType, fn($q) => $q->where('visit_type', $this->visitType))
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->get();

            foreach ($appointments as $app) {
                fputcsv($file, [
                    $app->appointment_date->format('Y-m-d'),
                    $app->appointment_time->format('H:i'),
                    $app->patient->first_name . ' ' . $app->patient->last_name,
                    $app->patient->card_number,
                    'Dr. ' . $app->doctor->name,
                    ucfirst(str_replace('-', ' ', $app->visit_type)),
                    ucfirst($app->status),
                    $app->checked_in_at ? $app->checked_in_at->format('H:i') : 'No',
                    $app->doctor_notes ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.appointment.all-appointments', [
            'appointments' => $this->appointments,
            'stats' => $this->stats,
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->orderBy('name')->get(),
            'statuses' => ['scheduled', 'completed', 'missed', 'cancelled', 'rescheduled'],
            'visitTypes' => [
                'consultation' => 'Consultation',
                'follow-up' => 'Follow-up',
                'lab_review' => 'Lab Review',
                'rehab_milestone' => 'Rehab Milestone',
                'emergency' => 'Emergency',
                'other' => 'Other',
            ],
        ]);
    }
}