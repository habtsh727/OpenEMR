<?php
// app/Livewire/Doctor/EncounterList.php

namespace App\Livewire\Doctor;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Encounter;
use App\Models\RehabEncounter;

class EncounterList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;

    protected $queryString = ['search', 'statusFilter'];

    public function orderRehab($encounterId)
    {
        $encounter = Encounter::findOrFail($encounterId);
        
        // Check if rehab encounter already exists
        $rehabEncounter = RehabEncounter::where('encounter_id', $encounter->id)->first();
        
        if (!$rehabEncounter) {
            $rehabEncounter = RehabEncounter::create([
                'encounter_id' => $encounter->id,
                'status' => 'pending_questionnaire',
            ]);
            
            $this->dispatch('notify', 
                type: 'success',
                message: 'Rehabilitation ordered successfully!'
            );
        } else {
            $this->dispatch('notify', 
                type: 'warning',
                message: 'Rehabilitation already ordered for this encounter.'
            );
        }
    }

    public function render()
    {
        $query = Encounter::where('doctor_id', auth()->id())
            ->whereIn('status', ['in_progress', 'doctor_assigned'])
            ->with(['patient', 'doctor', 'rehabEncounter']);

        if ($this->search) {
            $query->whereHas('patient', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $encounters = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.doctor.encounter-list', compact('encounters'));
    }
}