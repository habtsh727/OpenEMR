<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CuppingTherapy;
use App\Models\CuppingSession;
use Illuminate\Support\Facades\DB;

class CuppingResult extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    // Modal
    public $showResultModal = false;
    public $selectedTherapy = null;
    public $therapyDetails = null;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = [
        'search' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
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

    public function viewResult($therapyId)
    {
        $this->selectedTherapy = CuppingTherapy::with([
            'encounter.patient',
            'sessions' => function ($query) {
                $query->orderBy('session_number');
            },
            'sessions.therapyPackage',
            'sessions.report'
        ])->find($therapyId);

        if (!$this->selectedTherapy) {
            $this->showAlertMessage('Therapy not found', 'error');
            return;
        }

        $this->therapyDetails = [
            'therapy' => $this->selectedTherapy,
            'patient' => $this->selectedTherapy->encounter->patient,
            'sessions' => [],
        ];

        foreach ($this->selectedTherapy->sessions as $session) {
            $treatments = json_decode($session->therapyPackage->treatment_snapshot ?? '[]', true);
            $materials = json_decode($session->therapyPackage->materials_snapshot ?? '[]', true);
            $materialsConsumed = json_decode($session->materials_consumed ?? '[]', true);

            $this->therapyDetails['sessions'][] = [
                'session' => $session,
                'package_name' => $session->therapyPackage->package_name_snapshot ?? 'N/A',
                'treatments' => $treatments,
                'materials' => $materials,
                'materials_consumed' => $materialsConsumed,
                'report' => $session->report,
            ];
        }

        $this->showResultModal = true;
    }

    public function closeModal()
    {
        $this->showResultModal = false;
        $this->selectedTherapy = null;
        $this->therapyDetails = null;
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        $query = CuppingTherapy::with([
            'encounter.patient',
            'sessions' => function ($query) {
                $query->orderBy('session_number');
            },
            'sessions.report',
            'sessions.therapyPackage'
        ]);

        // Only show therapies with at least one completed session
        $query->whereHas('sessions', function ($q) {
            $q->where('treatment_status', 'completed');
        });

        // Date filter
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Search filter
        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('card_number', 'like', '%' . $this->search . '%');
            });
        }

        $therapies = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Statistics
        $completedSessions = CuppingSession::where('treatment_status', 'completed')->count();
        $therapiesWithCompleted = CuppingTherapy::whereHas('sessions', function ($q) {
            $q->where('treatment_status', 'completed');
        })->count();
        $sessionsWithReports = CuppingSession::where('treatment_status', 'completed')
            ->whereHas('report')
            ->count();

        $stats = [
            'total_completed_sessions' => $completedSessions,
            'total_patients' => $therapiesWithCompleted,
            'total_with_reports' => $sessionsWithReports,
        ];

        return view('livewire.cupping.cupping-result', [
            'therapies' => $therapies,
            'stats' => $stats,
        ]);
    }
}
