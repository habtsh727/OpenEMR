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
    public $dateFilter = ''; // Add this property for date filtering
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
            'labResults' => function ($query) {
                $query->latest()->limit(5); // Get latest 5 results
            },
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
                        })
                        ->orWhereHas('labResults', function ($q2) {
                            $q2->where('result', 'like', '%' . $this->search . '%')
                                ->orWhere('notes', 'like', '%' . $this->search . '%');
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
            ->when($this->dateFilter, function ($query) {
                $startDate = now()->subDays($this->dateFilter)->startOfDay();
                $query->where('created_at', '>=', $startDate);
            })
            ->withCount(['labResults'])
            ->orderBy('updated_at', 'desc') // Changed to updated_at for latest activity
            ->orderBy('created_at', 'desc') // Then by creation date
            ->paginate(12);

        $patients = Patient::when($this->search, function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
        })
            ->orderBy('first_name')
            ->limit(50)
            ->get();

        // Calculate stats with the same filters
        $totalResults = $labOrders->total();
        $reportedCount = LabOrder::whereHas('order.encounter', function ($query) {
            if (auth()->user()->hasRole('doctor')) {
                $query->where('doctor_id', auth()->id());
            }
        })
            ->where('status', 'reported')
            ->when($this->dateFilter, function ($query) {
                $startDate = now()->subDays($this->dateFilter)->startOfDay();
                $query->where('created_at', '>=', $startDate);
            })
            ->count();

        $verifiedCount = LabOrder::whereHas('order.encounter', function ($query) {
            if (auth()->user()->hasRole('doctor')) {
                $query->where('doctor_id', auth()->id());
            }
        })
            ->where('status', 'verified')
            ->when($this->dateFilter, function ($query) {
                $startDate = now()->subDays($this->dateFilter)->startOfDay();
                $query->where('created_at', '>=', $startDate);
            })
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
