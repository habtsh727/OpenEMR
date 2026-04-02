<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\CuppingQueue;
use App\Models\CuppingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class CuppingPaymentQueue extends Component
{
    public $queueItems = [];
    public $selectedSession = null;
    public $showPaymentForm = false;
    public $selectedPatient = null;
    public $patientSessions = [];
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    public function mount()
    {
        $this->loadQueue();
    }

    public function loadQueue()
    {
        // Get queue items with relationships
        $queueItems = CuppingQueue::with([
            'cuppingSession.cuppingTherapy.encounter.patient',
            'cuppingSession.items.cuppingType',
            'cuppingSession.items.cuppingLocation'
        ])
            ->where('queue_type', 'payment')
            ->where('status', 'waiting')
            ->orderBy('position')
            ->get();
        
        // Manually group by patient
        $grouped = [];
        foreach ($queueItems as $item) {
            $patient = $item->cuppingSession->cuppingTherapy->encounter->patient;
            if ($patient) {
                $patientId = $patient->id;
                if (!isset($grouped[$patientId])) {
                    $grouped[$patientId] = [
                        'patient' => $patient,
                        'items' => []
                    ];
                }
                $grouped[$patientId]['items'][] = $item;
            }
        }
        
        $this->queueItems = $grouped;
    }

    public function viewPatientPayments($patientId, $patientName)
    {
        $this->selectedPatient = [
            'id' => $patientId,
            'name' => $patientName
        ];
        
        // Get all unpaid sessions for this patient
        $this->patientSessions = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'items.cuppingType',
            'items.cuppingLocation',
            'payments'
        ])
            ->whereHas('cuppingTherapy.encounter.patient', function($query) use ($patientId) {
                $query->where('id', $patientId);
            })
            ->where('payment_status', '!=', 'paid')
            ->orderBy('session_date')
            ->get();
    }

    public function processPayment($sessionId)
    {
        $this->selectedSession = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'items.cuppingType',
            'items.cuppingLocation'
        ])->find($sessionId);
        
        $this->showPaymentForm = true;
    }

    public function closePaymentForm()
    {
        $this->showPaymentForm = false;
        $this->selectedSession = null;
        $this->loadQueue();
    }

    public function backToQueue()
    {
        $this->selectedPatient = null;
        $this->patientSessions = [];
        $this->loadQueue();
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.cashier.cupping-payment-queue');
    }
}