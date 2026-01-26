<?php

namespace App\Livewire\OrderLab;

use App\Models\Patient;
use App\Models\LabOrder;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class DoctorLabResults extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPatient = '';
    public $patient = null;
    public $selectedResult = null;

    public function mount(Patient $patient = null)
    {
        $this->authorize('view_lab_result');

        if ($patient) {
            $this->patient = $patient;
            $this->filterPatient = $patient->id;
        }
    }

    public function viewResult($labOrderId)
    {
        $this->selectedResult = $labOrderId;
        $this->dispatch('open-modal', 'view-result');
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterPatient']);
    }

    public function render()
    {
        $labOrders = LabOrder::with([
            'order.encounter.patient',
            'labTest',
            'labResults',
            'labSamples'
        ])
            ->whereHas('order.encounter', function ($query) {
                if (auth()->user()->hasRole('doctor')) {
                    $query->where('doctor_id', auth()->id());
                }
            })
            ->whereIn('status', ['reported', 'verified'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('labTest', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('order.encounter.patient', function ($q2) {
                        $q2->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterPatient, function ($query) {
                $query->whereHas('order.encounter.patient', function ($q) {
                    $q->where('id', $this->filterPatient);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $patients = Patient::when($this->search, function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('first_name')
            ->limit(50)
            ->get();

        // Calculate stats
        $totalResults = $labOrders->total();
        $reportedCount = LabOrder::whereHas('order.encounter', function ($query) {
                if (auth()->user()->hasRole('doctor')) {
                    $query->where('doctor_id', auth()->id());
                }
            })
            ->where('status', 'reported')
            ->count();
        $verifiedCount = LabOrder::whereHas('order.encounter', function ($query) {
                if (auth()->user()->hasRole('doctor')) {
                    $query->where('doctor_id', auth()->id());
                }
            })
            ->where('status', 'verified')
            ->count();

        return view('livewire.order-lab.doctor-lab-results', [
            'labOrders' => $labOrders,
            'patients' => $patients,
            'totalResults' => $totalResults,
            'reportedCount' => $reportedCount,
            'verifiedCount' => $verifiedCount,
        ]);
    }
}