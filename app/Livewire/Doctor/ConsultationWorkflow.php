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
        if ($encounter->doctor_id != Auth::id()) {
            abort(403, 'Not authorized to access this consultation.');
        }

        // Load vitals with their types
        $this->encounter = $encounter->load([
            'patient', 
            'medicalHistories.template',
            'vitals.vitalType',  // Add this
            'triageBy'           // Add this
        ]);
        
        $this->loadMedicalHistoryForm();
    }

    // Get all collected vitals for this encounter
    public function getCollectedVitalsProperty()
    {
        return $this->encounter->vitals
            ->sortBy('vitalType.sort_order')
            ->map(function ($vital) {
                return [
                    'id' => $vital->id,
                    'name' => $vital->vitalType->name,
                    'slug' => $vital->vitalType->slug,
                    'value' => $vital->value,
                    'unit' => $vital->vitalType->unit,
                    'data_type' => $vital->vitalType->data_type,
                    'icon' => $this->getVitalIcon($vital->vitalType->slug),
                    'color_class' => $this->getVitalColorClass($vital->vitalType->slug),
                ];
            })
            ->values(); // Reset keys
    }

    // Helper to get icon based on vital slug
    private function getVitalIcon($slug)
    {
        return match ($slug) {
            'bp_systolic', 'bp_diastolic' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'temperature' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
            'pulse_rate' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
            'spo2' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z',
            'respiratory_rate' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
            'height' => 'M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z',
            'weight' => 'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
            'bmi' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
            'blood_glucose' => 'M19 14l-7 7m0 0l-7-7m7 7V3',
            default => 'M13 10V3L4 14h7v7l9-11h-7z' // Default heartbeat icon
        };
    }

    // Helper to get color class based on vital slug
    private function getVitalColorClass($slug)
    {
        return match ($slug) {
            'bp_systolic', 'bp_diastolic' => 'blue',
            'temperature' => 'green',
            'pulse_rate' => 'purple',
            'spo2' => 'amber',
            'respiratory_rate' => 'indigo',
            'height', 'weight', 'bmi' => 'teal',
            'blood_glucose' => 'red',
            default => 'gray'
        };
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
    
    public function skipMedicalHistory()
    {
        return $this->redirect(route('consultation.chief-complaint', $this->encounter), navigate: true);
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