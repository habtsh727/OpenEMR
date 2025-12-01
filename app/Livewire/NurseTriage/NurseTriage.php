<?php

namespace App\Livewire\NurseTriage;

use Livewire\Component;
use App\Models\Patient;

class NurseTriage extends Component
{

    public Patient $patient;

    public $bp_systolic;
    public $bp_diastolic;
    public $temperature;
    public $pulse;
    public $spo2;
    public $priority = 'normal'; // normal, high
    public $patient_id;


     public function mount(Patient $patient)
    {
        $this->patient = $patient;
    }

    public function save()
    {
        $this->validate([
            'bp_systolic' => 'required|numeric|min:50|max:250',
            'bp_diastolic' => 'required|numeric|min:30|max:150',
            'temperature' => 'required|numeric|min:30|max:45',
            'pulse' => 'required|numeric|min:30|max:200',
            'spo2' => 'required|numeric|min:50|max:100',
            'priority' => 'required|in:normal,high',
        ]);

        \App\Models\NurseTriage::create([
            'patient_id' => $this->patient->id,
            'bp_systolic' => $this->bp_systolic,
            'bp_diastolic' => $this->bp_diastolic,
            'temperature' => $this->temperature,
            'pulse' => $this->pulse,
            'spo2' => $this->spo2,
            'priority' => $this->priority,
            'processed_by' => auth()->id(),
        ]);

        session()->flash('success', 'Triage saved successfully.');

        if ($this->priority === 'high') {
           dd("Emergency");
        }

        // Otherwise, continue to next step
        return redirect()->route('patient.nursing');
    }



    public function render()
    {
        return view('livewire.nurse-triage.nurse-triage');
    }
}
