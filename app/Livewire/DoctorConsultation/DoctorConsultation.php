<?php

namespace App\Livewire\DoctorConsultation;

use Livewire\Component;
use App\Models\DoctorQueue;
use App\Models\PharmacyItem;

class DoctorConsultation extends Component
{
    public DoctorQueue $queue;
    public $step = 1;

    public array $history = [];
    public array $current_complaints = [];
    public array $physical_exam = [];
    public array $assessment_options = [];
    public string $assessment_notes = '';
    public array $lab_orders = [];
    public array $imaging_orders = [];
    public array $medications = [];

    public string $history_search = '';
    public string $complaints_search = '';
    public string $exam_search = '';
    public string $assessment_search = '';
    public string $lab_search = '';
    public string $imaging_search = '';
    public string $medications_search = '';

    public array $pharmacyitems = [];

    public function mount(DoctorQueue $queue)
    {
        $this->queue = $queue;
        $this->pharmacyitems = PharmacyItem::all()->pluck('name')->toArray();
    }

    public function toggleHistory($item)
    {
        if (in_array($item, $this->history)) {
            $this->history = array_diff($this->history, [$item]);
        } else {
            $this->history[] = $item;
        }
    }

    public function toggleComplaint($item)
    {
        if (in_array($item, $this->current_complaints)) {
            $this->current_complaints = array_diff($this->current_complaints, [$item]);
        } else {
            $this->current_complaints[] = $item;
        }
    }

    public function toggleExam($item)
    {
        if (in_array($item, $this->physical_exam)) {
            $this->physical_exam = array_diff($this->physical_exam, [$item]);
        } else {
            $this->physical_exam[] = $item;
        }
    }

    public function toggleAssessment($item)
    {
        if (in_array($item, $this->assessment_options)) {
            $this->assessment_options = array_diff($this->assessment_options, [$item]);
        } else {
            $this->assessment_options[] = $item;
        }
    }

    public function toggleLabOrder($item)
    {
        if (in_array($item, $this->lab_orders)) {
            $this->lab_orders = array_diff($this->lab_orders, [$item]);
        } else {
            $this->lab_orders[] = $item;
        }
    }

    public function toggleImaging($item)
    {
        if (in_array($item, $this->imaging_orders)) {
            $this->imaging_orders = array_diff($this->imaging_orders, [$item]);
        } else {
            $this->imaging_orders[] = $item;
        }
    }

    public function toggleMedication($item)
    {
        if (in_array($item, $this->medications)) {
            $this->medications = array_diff($this->medications, [$item]);
        } else {
            $this->medications[] = $item;
        }
    }

    public function nextStep()
    {
        $this->step = min(5, $this->step + 1);
    }

    public function previousStep()
    {
        $this->step = max(1, $this->step - 1);
    }

    public function saveConsultation()
{
    $this->queue->consultation()->create([
        'doctor_id' => auth()->id(),
        'patient_id' => $this->queue->patient_id,
        'history' => json_encode($this->history),
        'current_complaints' => json_encode($this->current_complaints),
        'physical_exam' => json_encode($this->physical_exam),
        'assessment_options' => json_encode($this->assessment_options),
        'assessment_notes' => $this->assessment_notes,
        'lab_orders' => json_encode($this->lab_orders),
        'imaging_orders' => json_encode($this->imaging_orders),
        'medications' => json_encode($this->medications),
        // Optional: If you want diagnosis, plan, disposition fields
        'diagnosis' => json_encode($this->assessment_options), // Or compute from assessment
        'plan' => null,
        'disposition' => null,
    ]);

    session()->flash('message', 'Consultation saved successfully!');
    $this->redirect(route('doctor.queue'));
}

    public function render()
    {
        return view('livewire.doctor-consultation.doctor-consultation');
    }
}
