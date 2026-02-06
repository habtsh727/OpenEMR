<?php

namespace App\Livewire\Encounters;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Encounter;
use App\Models\User;
use App\Models\VitalType;
use App\Models\EncounterVital;

class TriageIndex extends Component
{
    use WithPagination;

    public $showProcessModal = false;
    public $selectedEncounterId = null;
    public $vitalValues = []; // Dynamic vital values
    public $availableVitalTypes = [];
    
    // Keep these for backward compatibility if needed
    public $priority = '';
    public $doctor_id = '';
    public $notes = '';

    public $statusFilter = 'all';

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        // Load all active vital types
        $this->availableVitalTypes = VitalType::active()
            ->orderBy('sort_order')
            ->get();
            
        // Initialize vital values array
        foreach ($this->availableVitalTypes as $type) {
            $this->vitalValues[$type->id] = '';
        }
    }

    public function openProcessModal($encounterId)
    {
        $this->selectedEncounterId = $encounterId;
        $this->showProcessModal = true;

        // Reset all values
        $this->priority = '';
        $this->doctor_id = '';
        $this->notes = '';
        
        // Reset vital values
        foreach ($this->availableVitalTypes as $type) {
            $this->vitalValues[$type->id] = '';
        }

        // Load existing vitals if encounter already has some
        $encounter = Encounter::with('vitals')->find($encounterId);
        if ($encounter && $encounter->vitals) {
            foreach ($encounter->vitals as $existingVital) {
                $this->vitalValues[$existingVital->vital_type_id] = $existingVital->value;
            }
            
            // Also load priority if exists
            if ($encounter->priority) {
                $this->priority = $encounter->priority;
            }
            
            if ($encounter->doctor_id) {
                $this->doctor_id = $encounter->doctor_id;
            }
        }
    }

    public function closeModal()
    {
        $this->showProcessModal = false;
        $this->selectedEncounterId = null;
        $this->reset(['priority', 'doctor_id', 'notes']);
        foreach (array_keys($this->vitalValues) as $key) {
            $this->vitalValues[$key] = '';
        }
    }

    public function processTriage()
    {
        // Validate priority and doctor assignment
        $this->validate([
            'priority' => 'required|in:low,medium,high,critical',
            'doctor_id' => 'required|exists:users,id',
        ], [
            'priority.required' => 'Priority level is required',
            'doctor_id.required' => 'Please assign a doctor',
            'doctor_id.exists' => 'Selected doctor does not exist',
        ]);

        // Validate each vital based on its data type
        foreach ($this->availableVitalTypes as $vitalType) {
            $value = $this->vitalValues[$vitalType->id] ?? '';
            
            if ($value !== '') {
                $rules = $this->getValidationRulesForVitalType($vitalType);
                $this->validateOnly("vitalValues.{$vitalType->id}", $rules);
            }
        }

        try {
            $encounter = Encounter::findOrFail($this->selectedEncounterId);

            // Save all vitals
            foreach ($this->availableVitalTypes as $vitalType) {
                $value = $this->vitalValues[$vitalType->id] ?? '';
                
                if ($value !== '') {
                    $encounter->addVital(
                        $vitalType->id,
                        $value,
                        auth()->id()
                    );
                }
            }

            // Update encounter status and triage info
            $encounter->update([
                'priority' => $this->priority,
                'doctor_id' => $this->doctor_id,
                'triage_by' => auth()->id(),
                'processed_by' => auth()->user()->name,
                'status' => 'triaged',
            ]);

            session()->flash('message', 'Triage completed successfully. Patient assigned to doctor.');

            $this->closeModal();
            $this->resetPage();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error processing triage: ' . $e->getMessage());
        }
    }

    private function getValidationRulesForVitalType(VitalType $vitalType)
    {
        $rules = [];
        
        switch ($vitalType->data_type) {
            case 'number':
                $rules["vitalValues.{$vitalType->id}"] = 'numeric';
                // Add min/max based on vital type slug if needed
                switch ($vitalType->slug) {
                    case 'bp_systolic':
                        $rules["vitalValues.{$vitalType->id}"] .= '|min:50|max:250';
                        break;
                    case 'bp_diastolic':
                        $rules["vitalValues.{$vitalType->id}"] .= '|min:30|max:150';
                        break;
                    case 'temperature':
                        $rules["vitalValues.{$vitalType->id}"] .= '|min:30|max:45';
                        break;
                    case 'pulse_rate':
                        $rules["vitalValues.{$vitalType->id}"] .= '|min:30|max:200';
                        break;
                    case 'spo2':
                        $rules["vitalValues.{$vitalType->id}"] .= '|min:70|max:100';
                        break;
                }
                break;
                
            case 'boolean':
                $rules["vitalValues.{$vitalType->id}"] = 'in:yes,no,1,0';
                break;
                
            case 'select':
                $rules["vitalValues.{$vitalType->id}"] = Rule::in($vitalType->options ?? []);
                break;
                
            default:
                $rules["vitalValues.{$vitalType->id}"] = 'max:255';
                break;
        }
        
        return $rules;
    }

    public function getSelectedEncounterProperty()
    {
        if (!$this->selectedEncounterId) {
            return null;
        }

        return Encounter::with(['patient', 'vitals.vitalType'])->find($this->selectedEncounterId);
    }

    public function getDoctorsProperty()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'doctor');
        })
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function render()
    {
        // Show pending encounters for patients who have paid
        $encounters = Encounter::where(function ($query) {
                $query->whereNull('card_payment_id')
                    ->orWhereHas('cardPayment', function ($q) {
                        $q->where('is_paid', true);
                    });
            })
            ->where('status', 'pending')
            ->with(['patient', 'vitals.vitalType'])
            ->latest()
            ->paginate(15);

        return view('livewire.encounters.triage-index', [
            'encounters' => $encounters,
        ]);
    }
}