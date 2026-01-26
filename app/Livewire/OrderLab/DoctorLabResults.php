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
    public $filterStatus = 'reported';
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
        // Dispatch browser event to open modal
        $this->dispatch('open-modal', 'view-result');
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
                // Doctor can only see results from encounters they're assigned to
                if (auth()->user()->hasRole('doctor')) {
                    $query->where('doctor_id', auth()->id());
                }
            })
            ->whereIn('status', ['reported', 'verified']) // Only show completed results
            ->when($this->search, function ($query) {
                $query->whereHas('labTest', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
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
            ->paginate(10);

        // Get patients for filter dropdown
        $patients = Patient::when($this->search, function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
        })
            ->orderBy('first_name')
            ->limit(50)
            ->get();

        return view('livewire.order-lab.doctor-lab-results', [
            'labOrders' => $labOrders,
            'patients' => $patients,
        ]);
    }
}
