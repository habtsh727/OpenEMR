<?php

namespace App\Livewire\CuppingDepartment;

use Livewire\Component;
use App\Models\CuppingQueue;
use App\Models\CuppingSession;
use Illuminate\Support\Facades\Auth;

class TreatmentQueue extends Component
{
    public $queueItems = [];
    public $selectedSession = null;
    public $showReportForm = false;

    public function mount()
    {
        $this->loadQueue();
    }

    public function loadQueue()
    {
        $this->queueItems = CuppingQueue::with(['cuppingSession.cuppingTherapy.encounter.patient', 'cuppingSession.items'])
            ->where('queue_type', 'treatment')
            ->where('status', 'waiting')
            ->orderBy('position')
            ->get();
    }

    public function startTreatment($sessionId)
    {
        $session = CuppingSession::find($sessionId);
        $session->startTreatment();
        
        $this->selectedSession = $session;
        $this->showReportForm = true;
        $this->loadQueue();
    }

    public function closeReportForm()
    {
        $this->showReportForm = false;
        $this->selectedSession = null;
        $this->loadQueue();
    }

    public function render()
    {
        return view('livewire.cupping-department.treatment-queue', [
            'queue' => $this->queueItems,
        ]);
    }
}