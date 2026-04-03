<?php

namespace App\Livewire\CuppingDepartment;

use Livewire\Component;
use App\Models\CuppingQueue;
use App\Models\CuppingSession;
use Illuminate\Support\Facades\Auth;

class TreatmentQueue extends Component
{
    public $queueItems = [];
    public $inProgressSessions = [];
    public $selectedSession = null;
    public $showReportForm = false;
    public $showSessionDetail = false;
    public $selectedDetailSession = null;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $listeners = [
        'close-modal' => 'closeReportForm',
        'show-success-message' => 'showSuccessMessage',
        'show-error-message' => 'showErrorMessage'
    ];

    public function mount()
    {
        $this->loadQueue();
        $this->loadInProgressSessions();
    }

    public function loadQueue()
    {
        // Get waiting queue items
        $this->queueItems = CuppingQueue::with([
            'cuppingSession.cuppingTherapy.encounter.patient',
            'cuppingSession.items.cuppingType',
            'cuppingSession.items.cuppingLocation'
        ])
            ->where('queue_type', 'treatment')
            ->where('status', 'waiting')
            ->orderBy('position')
            ->get();
    }

    public function loadInProgressSessions()
    {
        // Get sessions that are in progress (started but not completed)
        $this->inProgressSessions = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'items.cuppingType',
            'items.cuppingLocation',
            'queue'
        ])
            ->where('treatment_status', 'in_progress')
            ->orderBy('treatment_started_at', 'desc')
            ->get();
    }

    public function viewSessionDetail($sessionId)
    {
        $this->selectedDetailSession = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'items.cuppingType',
            'items.cuppingLocation',
            'cuppingTherapy.doctor'
        ])->find($sessionId);
        
        $this->showSessionDetail = true;
    }

    public function closeSessionDetail()
    {
        $this->showSessionDetail = false;
        $this->selectedDetailSession = null;
    }

    public function startTreatment($sessionId)
    {
        $session = CuppingSession::find($sessionId);
        $session->startTreatment();
        
        $this->selectedSession = $session;
        $this->showReportForm = true;
        $this->loadQueue();
        $this->loadInProgressSessions();
    }

    public function continueTreatment($sessionId)
    {
        $session = CuppingSession::find($sessionId);
        $this->selectedSession = $session;
        $this->showReportForm = true;
    }

    public function closeReportForm()
    {
        $this->showReportForm = false;
        $this->selectedSession = null;
        $this->loadQueue();
        $this->loadInProgressSessions();
    }

    public function backToQueueWithoutSubmit()
    {
        $this->showReportForm = false;
        $this->selectedSession = null;
        $this->loadQueue();
        $this->loadInProgressSessions();
        
        $this->showSuccessMessage('Treatment saved as in progress. You can continue later.');
    }

    public function showSuccessMessage($message)
    {
        $this->alertMessage = $message;
        $this->alertType = 'success';
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
        
        // Auto hide after 3 seconds
        $this->dispatch('auto-hide-alert');
    }

    public function showErrorMessage($message)
    {
        $this->alertMessage = $message;
        $this->alertType = 'error';
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
        
        // Auto hide after 5 seconds
        $this->dispatch('auto-hide-alert');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.cupping-department.treatment-queue', [
            'queue' => $this->queueItems,
            'inProgress' => $this->inProgressSessions
        ]);
    }
}