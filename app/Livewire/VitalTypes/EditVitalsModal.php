<?php

namespace App\Livewire\VitalTypes;


use Livewire\Component;
use App\Models\Encounter;
use App\Models\EncounterVital;
use App\Models\VitalType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditVitalsModal extends Component
{
    public $encounterId;
    public $encounter;
    public $vitals = [];
    public $showModal = false;
    public $editReason = '';
    public $editable = true;

    protected $listeners = ['openEditVitals' => 'loadVitals'];

    public function loadVitals($encounterId)
    {
        $this->encounterId = $encounterId;
        $this->encounter = Encounter::with([
            'patient',
            'vitals.vitalType',
            'triageBy'
        ])->findOrFail($encounterId);

        // Check if user has permission to edit
        $this->checkEditPermission();

        // Load existing vitals
        $this->loadExistingVitals();

        $this->showModal = true;
    }

    protected function checkEditPermission()
    {
        $user = auth()->user();
        
        $isTriageNurse = $this->encounter->triage_by === $user->id;
        $isAssignedDoctor = $this->encounter->doctor_id === $user->id;
        $isAdmin = $user->hasRole('admin') ?? false; // Adjust based on your role system
        
        $encounterActive = !in_array($this->encounter->status, ['completed', 'cancelled']);
        
        $this->editable = ($isTriageNurse || $isAssignedDoctor || $isAdmin) && $encounterActive;
    }

    protected function loadExistingVitals()
    {
        $vitalTypes = VitalType::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $existingVitals = $this->encounter->vitals->keyBy('vital_type_id');

        $this->vitals = [];

        foreach ($vitalTypes as $vitalType) {
            $existing = $existingVitals->get($vitalType->id);
            
            $this->vitals[$vitalType->id] = [
                'id' => $existing ? $existing->id : null,
                'vital_type_id' => $vitalType->id,
                'name' => $vitalType->name,
                'value' => $existing ? $existing->value : '',
                'unit' => $vitalType->unit,
                'data_type' => $vitalType->data_type,
                'options' => $vitalType->options,
                'original_value' => $existing ? $existing->value : '',
                'has_changes' => false,
            ];
        }
    }

    protected function rules()
    {
        $rules = [];
        
        foreach ($this->vitals as $vitalTypeId => $vitalData) {
            $vitalType = VitalType::find($vitalTypeId);
            
            if ($vitalType->data_type === 'number') {
                $rules["vitals.{$vitalTypeId}.value"] = 'nullable|numeric|min:0|max:999';
                
                // Add specific ranges
                switch ($vitalType->slug) {
                    case 'temperature':
                        $rules["vitals.{$vitalTypeId}.value"] = 'nullable|numeric|between:32,45';
                        break;
                    case 'bp_systolic':
                    case 'bp_diastolic':
                        $rules["vitals.{$vitalTypeId}.value"] = 'nullable|numeric|between:30,300';
                        break;
                    case 'pulse_rate':
                        $rules["vitals.{$vitalTypeId}.value"] = 'nullable|numeric|between:20,250';
                        break;
                    case 'spo2':
                        $rules["vitals.{$vitalTypeId}.value"] = 'nullable|numeric|between:0,100';
                        break;
                }
            } elseif ($vitalType->data_type === 'text') {
                $rules["vitals.{$vitalTypeId}.value"] = 'nullable|string|max:255';
            } elseif ($vitalType->data_type === 'boolean') {
                $rules["vitals.{$vitalTypeId}.value"] = 'nullable|in:0,1,yes,no,true,false';
            } elseif ($vitalType->data_type === 'select' && $vitalType->options) {
                $options = is_string($vitalType->options) 
                    ? json_decode($vitalType->options, true) 
                    : $vitalType->options;
                $rules["vitals.{$vitalTypeId}.value"] = 'nullable|in:' . implode(',', $options);
            }
        }
        
        $rules['editReason'] = 'required_if:editable,true|string|max:500';
        
        return $rules;
    }

    public function updated($propertyName)
    {
        if (str_starts_with($propertyName, 'vitals.')) {
            $parts = explode('.', $propertyName);
            $vitalTypeId = $parts[1];
            
            $currentValue = $this->vitals[$vitalTypeId]['value'];
            $originalValue = $this->vitals[$vitalTypeId]['original_value'];
            
            $this->vitals[$vitalTypeId]['has_changes'] = ($currentValue != $originalValue);
        }
    }

    public function saveVitals()
    {
        if (!$this->editable) {
            $this->dispatch('notify', [
                'message' => 'You cannot edit these vitals.',
                'type' => 'error'
            ]);
            return;
        }

        $this->validate();

        try {
            DB::transaction(function () {
                $changes = [];
                
                foreach ($this->vitals as $vitalTypeId => $vitalData) {
                    if (!$vitalData['has_changes']) {
                        continue;
                    }

                    $oldValue = $vitalData['original_value'];
                    $newValue = $vitalData['value'];

                    if ($vitalData['id']) {
                        $vital = EncounterVital::find($vitalData['id']);
                        
                        $changes[] = [
                            'vital' => $vitalData['name'],
                            'old' => $oldValue,
                            'new' => $newValue,
                        ];

                        $vital->update([
                            'value' => $newValue,
                            'user_id' => auth()->id(),
                        ]);
                    } else {
                        EncounterVital::create([
                            'encounter_id' => $this->encounter->id,
                            'vital_type_id' => $vitalTypeId,
                            'value' => $newValue,
                            'user_id' => auth()->id(),
                        ]);

                        $changes[] = [
                            'vital' => $vitalData['name'],
                            'old' => 'Not recorded',
                            'new' => $newValue,
                        ];
                    }
                }

                if (!empty($changes) && !empty($this->editReason)) {
                    Log::info('Vitals edited', [
                        'encounter_id' => $this->encounter->id,
                        'patient_id' => $this->encounter->patient_id,
                        'edited_by' => auth()->id(),
                        'reason' => $this->editReason,
                        'changes' => $changes,
                    ]);
                }

                // Update encounter priority
                $this->updateEncounterPriority();

                $this->dispatch('vitalsUpdated');
                $this->dispatch('notify', [
                    'message' => 'Vitals updated successfully!',
                    'type' => 'success'
                ]);

                $this->showModal = false;
                $this->reset(['editReason', 'encounterId', 'encounter', 'vitals']);

            });
        } catch (\Exception $e) {
            Log::error('Failed to update vitals: ' . $e->getMessage());
            $this->dispatch('notify', [
                'message' => 'Failed to update vitals. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    protected function updateEncounterPriority()
    {
        $vitals = $this->encounter->vitals()->with('vitalType')->get();
        
        $priority = 'low';
        $criticalFindings = [];
        
        foreach ($vitals as $vital) {
            $value = $vital->value;
            
            if (!is_numeric($value)) continue;
            
            $value = floatval($value);
            
            switch ($vital->vitalType->slug) {
                case 'bp_systolic':
                    if ($value > 180) $criticalFindings[] = 'Critical BP';
                    elseif ($value > 160) $priority = max($priority, 'high');
                    elseif ($value > 140) $priority = max($priority, 'medium');
                    break;
                    
                case 'bp_diastolic':
                    if ($value > 110) $criticalFindings[] = 'Critical BP';
                    elseif ($value > 100) $priority = max($priority, 'high');
                    elseif ($value > 90) $priority = max($priority, 'medium');
                    break;
                    
                case 'temperature':
                    if ($value > 39.5 || $value < 35) $criticalFindings[] = 'Critical temp';
                    elseif ($value > 38.5 || $value < 36) $priority = max($priority, 'high');
                    elseif ($value > 37.5) $priority = max($priority, 'medium');
                    break;
                    
                case 'spo2':
                    if ($value < 85) $criticalFindings[] = 'Critical O2';
                    elseif ($value < 90) $priority = max($priority, 'high');
                    elseif ($value < 95) $priority = max($priority, 'medium');
                    break;
                    
                case 'pulse_rate':
                    if ($value > 140 || $value < 40) $criticalFindings[] = 'Critical pulse';
                    elseif ($value > 120 || $value < 50) $priority = max($priority, 'high');
                    elseif ($value > 100 || $value < 60) $priority = max($priority, 'medium');
                    break;
            }
        }
        
        $newPriority = !empty($criticalFindings) ? 'critical' : $priority;
        
        if ($this->encounter->priority !== $newPriority) {
            $this->encounter->update(['priority' => $newPriority]);
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['editReason', 'encounterId', 'encounter', 'vitals']);
    }

    public function render()
    {
        return view('livewire.vital-types.edit-vitals-modal');
    }
}
   

