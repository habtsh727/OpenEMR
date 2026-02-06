<?php

namespace App\Livewire\Encounters;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\VitalType;
use App\Models\EncounterVital;
use Illuminate\Validation\Rule;

class VitalSigns extends Component
{
    public $encounter;
    public $vitals = [];
    public $newVital = [
        'vital_type_id' => '',
        'value' => ''
    ];
    public $availableVitalTypes = [];

    protected $listeners = ['encounterSelected'];

    public function mount($encounterId = null)
    {
        if ($encounterId) {
            $this->encounter = Encounter::with('vitals.vitalType')->find($encounterId);
            $this->loadVitals();
        }

        $this->availableVitalTypes = VitalType::active()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'data_type' => $type->data_type,
                    'unit' => $type->unit,
                    'options' => $type->getOptionsArray()
                ];
            });
    }

    public function encounterSelected($encounterId)
    {
        $this->encounter = Encounter::with('vitals.vitalType')->find($encounterId);
        $this->loadVitals();
    }

    private function loadVitals()
    {
        $this->vitals = [];

        if (!$this->encounter) return;

        foreach ($this->encounter->vitals as $vital) {
            $this->vitals[] = [
                'id' => $vital->id,
                'vital_type_id' => $vital->vital_type_id,
                'name' => $vital->vitalType->name,
                'data_type' => $vital->vitalType->data_type,
                'value' => $vital->value,
                'unit' => $vital->vitalType->unit,
                'options' => $vital->vitalType->getOptionsArray(),
                'formatted' => $vital->getFormattedValue()
            ];
        }
    }

    public function addVital()
    {
        $this->validate([
            'newVital.vital_type_id' => 'required|exists:vital_types,id',
            'newVital.value' => ['required', $this->getValidationRule()]
        ], [], [
            'newVital.vital_type_id' => 'vital type',
            'newVital.value' => 'value'
        ]);

        try {
            $vitalType = VitalType::find($this->newVital['vital_type_id']);

            $this->encounter->addVital(
                $this->newVital['vital_type_id'],
                $this->newVital['value'],
                auth()->id()
            );

            $this->newVital = ['vital_type_id' => '', 'value' => ''];
            $this->loadVitals();

            $this->dispatch('vital-added', message: 'Vital sign added successfully');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to add vital sign: ' . $e->getMessage());
        }
    }

    public function updateVital($index)
    {
        $vital = $this->vitals[$index];
        $vitalType = VitalType::find($vital['vital_type_id']);

        $rules = [
            'vitals.' . $index . '.value' => ['required', $this->getValidationRule($vitalType)]
        ];

        $this->validate($rules, [], [
            'vitals.' . $index . '.value' => 'value'
        ]);

        try {
            EncounterVital::where('id', $vital['id'])
                ->update([
                    'value' => $this->vitals[$index]['value'],
                    'user_id' => auth()->id()
                ]);

            $this->loadVitals();
            $this->dispatch('vital-updated', message: 'Vital sign updated successfully');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to update vital sign');
        }
    }

    public function deleteVital($vitalId)
    {
        try {
            EncounterVital::where('id', $vitalId)->delete();
            $this->loadVitals();
            $this->dispatch('vital-deleted', message: 'Vital sign deleted successfully');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Failed to delete vital sign');
        }
    }

    private function getValidationRule($vitalType = null)
    {
        if (!$vitalType && $this->newVital['vital_type_id']) {
            $vitalType = VitalType::find($this->newVital['vital_type_id']);
        }

        if (!$vitalType) return 'max:255';

        return match ($vitalType->data_type) {
            'number' => 'numeric',
            'boolean' => Rule::in(['yes', 'no', '1', '0', true, false]),
            'select' => Rule::in($vitalType->getOptionsArray()),
            default => 'max:255'
        };
    }
    public function render()
    {
        return view('livewire.encounters.vital-signs');
    }
}
