<?php
// app/Livewire/Appointment/AllAppointments.php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class AllAppointments extends Component
{
    use WithPagination;

    public $search = '';
    public $doctorId = '';
    public $status = '';
    public $visitType = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 15;
    public $sortField = 'appointment_date';
    public $sortDirection = 'desc';

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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
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

        return [
            'total' => (clone $query)->count(),
            'scheduled' => (clone $query)->where('status', 'scheduled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
        ];
    }

    public function resetFilters()
    {
        $this->reset(['search', 'doctorId', 'status', 'visitType']);
        $this->dateFrom = Carbon::now('Africa/Addis_Ababa')->startOfMonth()->format('Y-m-d');
        $this->dateTo = Carbon::now('Africa/Addis_Ababa')->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.appointment.all-appointments', [
            'appointments' => $this->appointments,
            'stats' => $this->stats,
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
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