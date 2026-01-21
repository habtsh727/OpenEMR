<?php

namespace App\Livewire\Encounters;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Encounter;
use App\Models\User;
use App\Models\CardPayment;

class TriageIndex extends Component
{
    use WithPagination;

    public $showProcessModal = false;
    public $selectedEncounterId = null;
    public $vitals = [
        'bp_systolic' => '',
        'bp_diastolic' => '',
        'temperature' => '',
        'pulse' => '',
        'spo2' => '',
        'priority' => '',
        'doctor_id' => '',
        'notes' => ''
    ];

    public $statusFilter = 'all'; // Show all statuses by default

    protected $listeners = ['refresh' => '$refresh'];

    public function openProcessModal($encounterId)
    {
        $this->selectedEncounterId = $encounterId;
        $this->showProcessModal = true;

        // Reset vitals
        $this->vitals = [
            'bp_systolic' => '',
            'bp_diastolic' => '',
            'temperature' => '',
            'pulse' => '',
            'spo2' => '',
            'priority' => '',
            'doctor_id' => '',
            'notes' => ''
        ];
    }

    public function closeModal()
    {
        $this->showProcessModal = false;
        $this->selectedEncounterId = null;
        $this->reset('vitals');
    }

    public function processTriage()
    {
        $this->validate([
            'vitals.bp_systolic' => 'nullable|integer|min:50|max:250',
            'vitals.bp_diastolic' => 'nullable|integer|min:30|max:150',
            'vitals.temperature' => 'nullable|numeric|min:30|max:45',
            'vitals.pulse' => 'nullable|integer|min:30|max:200',
            'vitals.spo2' => 'nullable|integer|min:70|max:100',
            'vitals.priority' => 'required|in:low,medium,high,critical',
            'vitals.doctor_id' => 'required|exists:users,id',
        ], [
            'vitals.priority.required' => 'Priority level is required',
            'vitals.doctor_id.required' => 'Please assign a doctor',
            'vitals.doctor_id.exists' => 'Selected doctor does not exist',
        ]);

        // Find the encounter
        $encounter = Encounter::findOrFail($this->selectedEncounterId);

        // Update the encounter with triage data
        $encounter->update([
            'bp_systolic' => $this->vitals['bp_systolic'],
            'bp_diastolic' => $this->vitals['bp_diastolic'],
            'temperature' => $this->vitals['temperature'],
            'pulse' => $this->vitals['pulse'],
            'spo2' => $this->vitals['spo2'],
            'priority' => $this->vitals['priority'],
            'doctor_id' => $this->vitals['doctor_id'],
            'triage_by' => auth()->id(),
            'processed_by' => auth()->user()->name,
            'status' => 'triaged', // Status changes from 'pending' to 'triaged'
        ]);

        // Show success message
        session()->flash('message', 'Triage completed successfully. Patient assigned to doctor.');

        // Close modal and refresh data
        $this->closeModal();
        $this->resetPage();
    }

    public function getSelectedEncounterProperty()
    {
        if (!$this->selectedEncounterId) {
            return null;
        }

        return Encounter::with('patient')->find($this->selectedEncounterId);
    }

    public function getDoctorsProperty()
    {
        // Using Spatie's role relationship
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'doctor');
        })
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function render()
    {
        // Show all encounters for patients who have paid
        $encounters = Encounter::whereHas('patient.cardPayments', function ($query) {
                // Only include patients with at least one paid card payment
                $query->where('is_paid', true);
            })
            ->with(['patient' => function ($query) {
                $query->with(['cardPayments' => function ($q) {
                    $q->where('is_paid', true)->latest()->first();
                }]);
            }])
            ->when($this->statusFilter && $this->statusFilter !== 'all', function ($query) {
                // Filter by specific status if not 'all'
                return $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.encounters.triage-index', [
            'encounters' => $encounters,
        ]);
    }
}