<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\CuppingTherapy;
use App\Models\CuppingSession;
use App\Models\CuppingReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuppingReports extends Component
{
    public $reports = [];
    public $selectedReport = null;
    public $showReportDetail = false;
    public $search = '';
    public $statusFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search', 'statusFilter', 'dateFrom', 'dateTo'];

    public function mount()
    {
        $this->loadReports();
    }

    public function loadReports()
    {
        $query = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'cuppingTherapy.doctor',
            'report',
            'items.cuppingType',
            'items.cuppingLocation'
        ])
        ->whereHas('cuppingTherapy', function($q) {
            $q->where('doctor_id', Auth::id());
        })
        ->where('treatment_status', 'completed')
        ->whereHas('report');
        
        // Apply filters
        if ($this->search) {
            $query->whereHas('cuppingTherapy.encounter.patient', function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        
        if ($this->dateFrom) {
            $query->whereDate('session_date', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('session_date', '<=', $this->dateTo);
        }
        
        $this->reports = $query->orderBy('session_date', 'desc')->get();
    }

    public function updatedSearch()
    {
        $this->loadReports();
    }

    public function updatedDateFrom()
    {
        $this->loadReports();
    }

    public function updatedDateTo()
    {
        $this->loadReports();
    }

    public function viewReport($sessionId)
    {
        $this->selectedReport = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'cuppingTherapy.doctor',
            'report',
            'items.cuppingType',
            'items.cuppingLocation',
            'payments'
        ])->find($sessionId);
        
        $this->showReportDetail = true;
    }

    public function closeReportDetail()
    {
        $this->showReportDetail = false;
        $this->selectedReport = null;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->loadReports();
        $this->showAlertMessage('Filters cleared', 'info');
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
        return view('livewire.doctor.cupping-reports');
    }
}