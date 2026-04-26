<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CuppingSession;

class TreatmentCuppingQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $dateFilter = '';
    public $perPage = 10;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $listeners = ['refreshQueue' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function startSession($sessionId)
    {
        return redirect()->route('cupping.treatment.session', $sessionId);
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
        $query = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'therapyPackage'
        ])
        ->whereHas('cuppingTherapy', function ($q) {
            $q->whereIn('status', ['partial_paid', 'fully_paid', 'in_progress']);
        })
        ->where('payment_status', 'paid');

        if ($this->statusFilter === 'pending') {
            $query->where('treatment_status', 'pending');
        } elseif ($this->statusFilter === 'in_queue') {
            $query->where('treatment_status', 'in_queue');
        } elseif ($this->statusFilter === 'in_progress') {
            $query->where('treatment_status', 'in_progress');
        } elseif ($this->statusFilter === 'completed') {
            $query->where('treatment_status', 'completed');
        }

        if ($this->dateFilter === 'today') {
            $query->whereDate('session_date', today());
        } elseif ($this->dateFilter === 'tomorrow') {
            $query->whereDate('session_date', today()->addDay());
        } elseif ($this->dateFilter === 'this_week') {
            $query->whereBetween('session_date', [today()->startOfWeek(), today()->endOfWeek()]);
        }

        if ($this->search) {
            $query->whereHas('cuppingTherapy.encounter.patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('card_number', 'like', '%' . $this->search . '%');
            });
        }

        $sessions = $query->orderBy('session_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate($this->perPage);

        $position = 1;
        foreach ($sessions as $session) {
            if (in_array($session->treatment_status, ['pending', 'in_queue'])) {
                $session->queue_position = $position++;
            } else {
                $session->queue_position = null;
            }
        }

        $stats = [
            'total_pending' => CuppingSession::where('payment_status', 'paid')
                ->whereIn('treatment_status', ['pending', 'in_queue'])
                ->count(),
            'total_in_progress' => CuppingSession::where('treatment_status', 'in_progress')->count(),
            'total_today' => CuppingSession::whereDate('session_date', today())
                ->where('payment_status', 'paid')
                ->whereIn('treatment_status', ['pending', 'in_queue', 'in_progress'])
                ->count(),
            'total_completed_today' => CuppingSession::whereDate('treatment_completed_at', today())->count(),
        ];

        return view('livewire.cupping.treatment-cupping-queue', [
            'sessions' => $sessions,
            'stats' => $stats,
        ]);
    }
}
