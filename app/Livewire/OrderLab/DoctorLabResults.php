<?php

namespace App\Livewire\OrderLab;

use App\Models\Encounter;
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
    public $selectedEncounter = null;
    public $dateFilter = '';
    public $selectedEncounterForMedication = null;
    public function viewEncounterResults($encounterId)
    {
        $this->selectedEncounter = $encounterId;
        $this->dispatch('open-modal', 'view-encounter-results');
    }

    public function orderMedication($encounterId)
    {
        $this->selectedEncounterForMedication = $encounterId; // Use different property
        $this->dispatch('open-modal', 'order-medication');
    }
    public function mount(Patient $patient = null)
    {
        $this->authorize('view_lab_result');

        if ($patient) {
            $this->patient = $patient;
            $this->filterPatient = $patient->id;
        }
    }

    // public function viewEncounterResults($encounterId)
    // {
    //     $this->selectedEncounter = $encounterId;
    //     $this->dispatch('open-modal', 'view-encounter-results');
    // }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterPatient']);
    }

// public function orderMedication($encounterId)
// {
//     $this->selectedEncounter = $encounterId;
//     $this->dispatch('open-modal', 'order-medication'); // Changed to 'order-medication'
// }
    public function render()
    {
        // Get encounters with lab results
        $encounters = Encounter::with([
            'patient',
            'labOrders' => function ($query) {
                $query->with([
                    'labTest',
                    'labResults',
                    'order'
                ])->whereIn('lab_orders.status', ['reported', 'verified']); // Specify table
            }
        ])
            ->whereHas('labOrders', function ($query) {
                $query->whereIn('lab_orders.status', ['reported', 'verified']); // Specify table

                if (auth()->user()->hasRole('doctor')) {
                    $query->whereHas('order.encounter', function ($q) {
                        $q->where('encounters.doctor_id', auth()->id()); // Specify table
                    });
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('patient', function ($q2) {
                        $q2->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('labOrders.labTest', function ($q2) {
                            $q2->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('code', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('encounters.id', 'like', '%' . $this->search . '%'); // Specify table
                });
            })
            ->when($this->filterPatient, function ($query) {
                $query->where('patient_id', $this->filterPatient);
            })
            ->when($this->dateFilter, function ($query) {
                $startDate = now()->subDays($this->dateFilter)->startOfDay();
                $query->whereHas('labOrders', function ($q) use ($startDate) {
                    $q->where('lab_orders.created_at', '>=', $startDate); // Specify table
                });
            })
            ->withCount(['labOrders' => function ($query) {
                $query->whereIn('lab_orders.status', ['reported', 'verified']); // Specify table
            }])
            ->orderBy('encounters.created_at', 'desc') // Specify table
            ->paginate(10);

        // Calculate stats - need to update this to be encounter-based
        $totalEncounters = $encounters->total();

        // For total lab orders count with doctor filter
        $totalLabOrdersQuery = LabOrder::whereIn('status', ['reported', 'verified'])
            ->when(auth()->user()->hasRole('doctor'), function ($query) {
                $query->whereHas('order.encounter', function ($q) {
                    $q->where('doctor_id', auth()->id());
                });
            });

        if ($this->dateFilter) {
            $startDate = now()->subDays($this->dateFilter)->startOfDay();
            $totalLabOrdersQuery->where('created_at', '>=', $startDate);
        }

        if ($this->filterPatient) {
            $totalLabOrdersQuery->whereHas('order.encounter', function ($q) {
                $q->where('patient_id', $this->filterPatient);
            });
        }


        if ($this->search) {
            $totalLabOrdersQuery->where(function ($q) {
                $q->whereHas('order.encounter.patient', function ($q2) {
                    $q2->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('labTest', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $totalLabOrders = $totalLabOrdersQuery->count();

        // For patient filter dropdown
        $patients = Patient::when($this->search, function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
        })
            ->orderBy('first_name')
            ->limit(50)
            ->get();

        return view('livewire.order-lab.doctor-lab-results', [
            'encounters' => $encounters,
            'patients' => $patients,
            'totalEncounters' => $totalEncounters,
            'totalLabOrders' => $totalLabOrders,
        ]);
    }
}
