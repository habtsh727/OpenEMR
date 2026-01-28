<?php

namespace App\Livewire\Doctor;

use App\Models\Encounter;
use App\Models\ExaminationTemplate;
use App\Models\EncounterExamination;
use Livewire\Component;

class ExaminationForm extends Component
{

    public $encounter;
    public $patient;

    // Store examination values by template ID
    public $examinationValues = [];

    // For select options
    public $selectOptions = [];

    // For text/number inputs
    public $textValues = [];

    // For yes_no inputs
    public $yesNoValues = [];

    public function mount($encounter)
    {
        $this->encounter = Encounter::with(['patient', 'examinations.template'])->findOrFail($encounter);
        $this->patient = $this->encounter->patient;

        // Security check
        if ($this->encounter->doctor_id !== auth()->id()) {
            abort(403, 'This patient is not assigned to you.');
        }

        // if ($this->encounter->status !== 'in_progress') {
        //     abort(400, 'Patient is not in consultation.');
        // }

        // Load existing examination values
        $this->loadExistingExaminations();
    }
    public function skipExamination()
    {
        return $this->redirect(route('consultation.assessment', $this->encounter), navigate: true);
    }
    private function loadExistingExaminations()
    {
        // Load all active examination templates
        $templates = ExaminationTemplate::where('active', true)->get();

        foreach ($templates as $template) {
            // Find existing examination for this template
            $existing = $this->encounter->examinations()
                ->where('examination_template_id', $template->id)
                ->first();

            // Set initial values based on field type
            switch ($template->field_type) {
                case 'select':
                    $this->selectOptions[$template->id] = $existing?->value ?? '';
                    break;

                case 'yes_no':
                    $this->yesNoValues[$template->id] = $existing?->value ?? '';
                    break;

                case 'text':
                case 'number':
                    // For vitals, pre-fill from encounter if available
                    if ($template->name === 'Temperature (°C)' && !$existing && $this->encounter->temperature) {
                        $this->textValues[$template->id] = $this->encounter->temperature;
                    } elseif ($template->name === 'Blood Pressure' && !$existing && $this->encounter->bp_systolic) {
                        $value = trim($this->encounter->bp_systolic . '/' . $this->encounter->bp_diastolic, '/');
                        $this->textValues[$template->id] = $value;
                    } else {
                        $this->textValues[$template->id] = $existing?->value ?? '';
                    }
                    break;
            }
        }
    }

    public function saveExamination()
    {
        $templates = ExaminationTemplate::where('active', true)->get();

        foreach ($templates as $template) {
            $value = $this->getValueForTemplate($template);

            if ($value === null || $value === '') {
                continue;
            }

            EncounterExamination::updateOrCreate(
                [
                    'encounter_id' => $this->encounter->id,
                    'examination_template_id' => $template->id
                ],
                ['value' => $value]
            );
            session()->flash('success', 'Examination findings saved successfully.');
        }
    }

    private function getValueForTemplate($template)
    {
        switch ($template->field_type) {
            case 'select':
                return $this->selectOptions[$template->id] ?? null;

            case 'yes_no':
                return $this->yesNoValues[$template->id] ?? null;

            case 'text':
            case 'number':
                return $this->textValues[$template->id] ?? null;
        }

        return null;
    }





    public function getCompletedCount()
    {
        $templates = ExaminationTemplate::where('active', true)->get();
        $completed = 0;

        foreach ($templates as $template) {
            $value = $this->getValueForTemplate($template);
            if ($value !== null && $value !== '') {
                $completed++;
            }
        }

        return $completed;
    }
    public function getInProgressCount()
    {
        $templates = ExaminationTemplate::where('active', true)->get();
        $inProgress = 0;

        foreach ($templates as $template) {
            $value = $this->getValueForTemplate($template);
            if ($value === '') {
                $inProgress++;
            }
        }

        return $inProgress;
    }
    public function getPendingCount()
    {
        $total = ExaminationTemplate::where('active', true)->count();
        return $total - $this->getCompletedCount() - $this->getInProgressCount();
    }
    public function backToChiefComplaint()
    {
        // Save before leaving
        $this->saveExamination();

        return $this->redirect(route('consultation.chief-complaint', $this->encounter), navigate: true);
    }

    public function nextToAssessment()
    {
        $this->saveExamination();

        return $this->redirect(
            route('consultation.assessment', $this->encounter->id),
            navigate: true
        );
    }
    public function render()
    {
        $examinationSystems = ExaminationTemplate::where('active', true)
            ->with(['encounterExaminations' => function ($query) {
                $query->where('encounter_id', $this->encounter->id);
            }])
            ->orderBy('system')
            ->orderBy('name')
            ->get()
            ->groupBy('system');
        return view('livewire.doctor.examination-form', [
            'examinationSystems' => $examinationSystems
        ]);
    }
}
