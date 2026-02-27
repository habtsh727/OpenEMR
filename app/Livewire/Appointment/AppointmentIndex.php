<?php

namespace App\Livewire\Appointment;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;

class AppointmentIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $dateFrom = '';

    #[Url(history: true)]
    public $dateTo = '';

    #[Url(history: true)]
    public $doctorId = '';

    #[Url(history: true)]
    public $status = '';

    #[Url(history: true)]
    public $visitType = '';

    #[Url(history: true)]
    public $sortField = 'appointment_date';

    #[Url(history: true)]
    public $sortDirection = 'desc';

    #[Url(history: true)]
    public $perPage = 15;

    protected $queryString = ['search', 'dateFrom', 'dateTo', 'doctorId', 'status', 'visitType'];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
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

    public function resetFilters()
    {
        $this->reset(['search', 'doctorId', 'status', 'visitType']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function getStatsProperty()
    {
        return [
            'total' => Appointment::count(),
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'missed' => Appointment::where('status', 'missed')->count(),
            'today' => Appointment::forToday()->count(),
            'upcoming' => Appointment::upcoming()->count(),
            'requests' => Appointment::where('status', 'requested')->count(),
        ];
    }
    public function render()
    {
        $query = Appointment::with(['patient', 'doctor', 'encounter'])
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

        $query->orderBy($this->sortField, $this->sortDirection);

        $appointments = $query->paginate($this->perPage);

        $stats = [
            'total' => Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])->count(),
            'scheduled' => Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
                ->where('status', 'scheduled')->count(),
            'completed' => Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
                ->where('status', 'completed')->count(),
            'missed' => Appointment::whereBetween('appointment_date', [$this->dateFrom, $this->dateTo])
                ->where('status', 'missed')->count(),
        ];

        return view('livewire.appointment.appointment-index', [
            'appointments' => $appointments,
            'stats' => $stats,
            'doctors' => User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get(),
            'statuses' => ['scheduled', 'completed', 'missed', 'cancelled', 'rescheduled', 'requested'],
            'visitTypes' => ['consultation', 'follow-up', 'lab_review', 'rehab_milestone', 'emergency', 'other'],
        ]);
    }
}
