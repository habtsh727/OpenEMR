<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\MedicalHistoryTemplate;
use App\Models\EncounterMedicalHistory;
use Illuminate\Support\Facades\Auth;

class ConsultationWorkflow extends Component
{
    public Encounter $encounter;
    public $medicalHistories = [];
    public $isSubmitting = false;
    public $submitSuccess = false;
    public $currentStep = 1; // 1 = Vital Signs, 2 = Medical History

    public function mount(Encounter $encounter)
    {
        if ($encounter->doctor_id !== Auth::id()) {
            abort(403, 'Not authorized to access this consultation.');
        }

        $this->encounter = $encounter->load(['patient', 'medicalHistories.template']);
        $this->loadMedicalHistoryForm();
    }

    private function loadMedicalHistoryForm()
    {
        $templates = MedicalHistoryTemplate::where('is_active', true)->get();

        foreach ($templates as $template) {
            $existing = $this->encounter->medicalHistories
                ->where('medical_history_template_id', $template->id)
                ->first();

            $this->medicalHistories[$template->id] = [
                'template_id' => $template->id,
                'name' => $template->name,
                'field_type' => $template->field_type,
                'value' => $existing?->value,
                'placeholder' => $this->getPlaceholder($template->field_type)
            ];
        }
    }

    private function getPlaceholder($fieldType)
    {
        return match ($fieldType) {
            'text' => 'Enter details...',
            'number' => 'Enter value...',
            'date' => 'Select date',
            default => null
        };
    }

    public function saveMedicalHistory()
    {
        $this->isSubmitting = true;

        try {
            foreach ($this->medicalHistories as $templateId => $data) {
                if (isset($data['value']) && !empty(trim($data['value']))) {
                    EncounterMedicalHistory::updateOrCreate(
                        [
                            'encounter_id' => $this->encounter->id,
                            'medical_history_template_id' => $templateId
                        ],
                        [
                            'value' => trim($data['value'])
                        ]
                    );
                } else {
                    EncounterMedicalHistory::where('encounter_id', $this->encounter->id)
                        ->where('medical_history_template_id', $templateId)
                        ->delete();
                }
            }

            $this->submitSuccess = true;
            session()->flash('success', 'Medical history saved successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save medical history. Please try again.');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function goToStep($step)
    {
        $this->currentStep = $step;
    }

    public function nextStep()
    {
        if ($this->currentStep < 2) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function completeAndContinue()
    {
        $this->saveMedicalHistory();
        // Redirect to next consultation stage (e.g., chief complaint)
        return redirect()->route('consultation.chief-complaint', $this->encounter);
    }
    public function nextToChiefComplaint()
    {
        $this->saveMedicalHistory();
        return $this->redirect(route('consultation.chief-complaint', $this->encounter), navigate: true);
    }
    private function getPriorityClass($priority)
    {
        return match (strtolower($priority ?? '')) {
            'high' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'medium' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'low' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }
    public function render()
    {
        return view('livewire.doctor.consultation-workflow');
    }
}
