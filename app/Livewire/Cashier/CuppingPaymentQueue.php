<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\CuppingQueue;
use App\Models\CuppingSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class CuppingPaymentQueue extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 10;
    public $selectedSession = null;
    public $showPaymentForm = false;
    public $selectedPatient = null;
    public $patientAllSessions = [];
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search'];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function viewPatientPayments($patientId, $patientName)
    {
        $this->selectedPatient = [
            'id' => $patientId,
            'name' => $patientName
        ];
        
        // Get ALL sessions for this patient (both paid and unpaid)
        $this->patientAllSessions = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'items.cuppingType',
            'items.cuppingLocation',
            'payments'
        ])
        ->whereHas('cuppingTherapy.encounter.patient', function($query) use ($patientId) {
            $query->where('id', $patientId);
        })
        ->orderBy('session_date', 'desc')
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
        $this->resetPage();
    }

    public function backToQueue()
    {
        $this->selectedPatient = null;
        $this->patientAllSessions = [];
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

    public function getQueueStats()
    {
        $query = CuppingQueue::with('cuppingSession.cuppingTherapy.encounter.patient')
            ->where('queue_type', 'payment')
            ->where('status', 'waiting');
        
        $queueItems = $query->get();
        
        // Group by patient to get unique patients
        $uniquePatients = [];
        $totalDue = 0;
        
        foreach ($queueItems as $item) {
            $patientId = $item->cuppingSession->cuppingTherapy->encounter->patient_id;
            if (!isset($uniquePatients[$patientId])) {
                $uniquePatients[$patientId] = true;
            }
            $totalDue += $item->cuppingSession->remaining_amount;
        }
        
        return [
            'total_patients' => count($uniquePatients),
            'pending_sessions' => $queueItems->count(),
            'total_due' => $totalDue,
        ];
    }

    public function render()
    {
        $stats = $this->getQueueStats();
        
        // Get queue items grouped by patient
        $queueItems = CuppingQueue::with([
            'cuppingSession.cuppingTherapy.encounter.patient',
            'cuppingSession.items.cuppingType',
            'cuppingSession.items.cuppingLocation'
        ])
        ->where('queue_type', 'payment')
        ->where('status', 'waiting');
        
        // Apply search filter - Using first_name and last_name instead of name
        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $queueItems->whereHas('cuppingSession.cuppingTherapy.encounter.patient', function($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                  ->orWhere('last_name', 'like', $searchTerm)
                  ->orWhere('card_number', 'like', $searchTerm)
                  ->orWhere('phone_number1', 'like', $searchTerm)
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', $searchTerm);
            });
        }
        
        $queueItems = $queueItems->get();
        
        // Group by patient
        $groupedPatients = [];
        foreach ($queueItems as $item) {
            $patient = $item->cuppingSession->cuppingTherapy->encounter->patient;
            if ($patient) {
                $patientId = $patient->id;
                if (!isset($groupedPatients[$patientId])) {
                    $groupedPatients[$patientId] = [
                        'patient' => $patient,
                        'sessions' => [],
                        'total_due' => 0,
                        'oldest_position' => $item->position
                    ];
                }
                $groupedPatients[$patientId]['sessions'][] = $item->cuppingSession;
                $groupedPatients[$patientId]['total_due'] += $item->cuppingSession->remaining_amount;
                if ($item->position < $groupedPatients[$patientId]['oldest_position']) {
                    $groupedPatients[$patientId]['oldest_position'] = $item->position;
                }
            }
        }
        
        // Convert to collection and paginate manually
        $groupedCollection = collect($groupedPatients);
        $currentPage = request()->get('page', 1);
        $perPage = $this->perPage;
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedCollection->forPage($currentPage, $perPage),
            $groupedCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('livewire.cashier.cupping-payment-queue', [
            'patients' => $paginatedData,
            'stats' => $stats
        ]);
    }
}