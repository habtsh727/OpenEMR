<?php

namespace App\Livewire\Doctor;

use App\Models\Encounter;
use App\Models\AssessmentTemplate;
use App\Models\EncounterAssessment;
use Illuminate\Support\Collection;
use Livewire\Component;

class AssessmentForm extends Component
{
    public $encounter;
    public $patient;

    // For template selection - multiple selection by default
    public $selectedTemplateIds = [];
    public $searchTerm = '';

    // For custom diagnosis
    public $customDiagnosis = '';

    // For diagnosis details (applies to all selected)
    public $diagnosisType = 'primary';
    public $diagnosisCertainty = 'provisional';
    public $diagnosisNotes = '';

    // List of added diagnoses
    public $diagnoses = [];

    // For editing single diagnosis
    public $editingId = null;

    // Alert system
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'selectedTemplateIds' => 'array',
        'selectedTemplateIds.*' => 'integer',
        'customDiagnosis' => 'nullable|string|max:255',
        'diagnosisType' => 'required|in:primary,secondary,differential',
        'diagnosisCertainty' => 'required|in:provisional,confirmed',
        'diagnosisNotes' => 'nullable|string|max:1000',
    ];

    public function mount($encounter)
    {
        $this->encounter = Encounter::with(['patient', 'assessments.template'])->findOrFail($encounter);
        $this->patient = $this->encounter->patient;

        // Security check
        if ($this->encounter->doctor_id !== auth()->id()) {
            abort(403, 'This patient is not assigned to you.');
        }

        if ($this->encounter->status !== 'in_progress') {
            abort(400, 'Patient is not in consultation.');
        }

        // Ensure selectedTemplateIds is always an array
        $this->selectedTemplateIds = is_array($this->selectedTemplateIds)
            ? $this->selectedTemplateIds
            : [];

        // Load existing assessments
        $this->loadDiagnoses();
    }

    private function loadDiagnoses()
    {
        $this->diagnoses = $this->encounter->assessments()
            ->with('template')
            ->get()
            ->map(function ($assessment) {
                return [
                    'id' => $assessment->id,
                    'diagnosis' => $assessment->getDiagnosisAttribute(),
                    'type' => $assessment->type,
                    'certainty' => $assessment->certainty,
                    'notes' => $assessment->notes,
                    'is_custom' => !empty($assessment->custom_diagnosis),
                ];
            })->toArray();
    }

    public function addDiagnosis()
    {
        // Ensure selectedTemplateIds is an array
        $this->selectedTemplateIds = $this->ensureArray($this->selectedTemplateIds);

        // If we have selected templates, add all of them
        if (!empty($this->selectedTemplateIds)) {
            $this->addMultipleDiagnoses();
            return;
        }

        // If no templates selected but custom diagnosis is entered
        if (!empty(trim($this->customDiagnosis))) {
            $this->createCustomDiagnosis();
            return;
        }

        // If editing existing
        if ($this->editingId) {
            $this->updateSingleDiagnosis();
            return;
        }

        // No diagnosis selected or entered
        $this->addError('diagnosis', 'Please select or enter a diagnosis');
    }

    private function addMultipleDiagnoses()
    {
        $addedCount = 0;
        $this->selectedTemplateIds = $this->ensureArray($this->selectedTemplateIds);

        // Validate before saving
        $this->validate([
            'diagnosisType' => 'required|in:primary,secondary,differential',
            'diagnosisCertainty' => 'required|in:provisional,confirmed',
        ]);

        foreach ($this->selectedTemplateIds as $templateId) {
            // Check if already exists
            $exists = EncounterAssessment::where('encounter_id', $this->encounter->id)
                ->where('assessment_template_id', $templateId)
                ->exists();

            if (!$exists) {
                EncounterAssessment::create([
                    'encounter_id' => $this->encounter->id,
                    'assessment_template_id' => $templateId,
                    'custom_diagnosis' => null,
                    'type' => $this->diagnosisType,
                    'certainty' => $this->diagnosisCertainty,
                    'notes' => $this->diagnosisNotes,
                ]);
                $addedCount++;
            }
        }

        // Add custom diagnosis if provided
        if (!empty(trim($this->customDiagnosis))) {
            $this->createCustomDiagnosis();
            $addedCount++;
        }

        // Reset form and show success
        $this->resetForm();
        $this->loadDiagnoses();

        if ($addedCount > 0) {
            $this->showAlert("Successfully added {$addedCount} diagnosis(es)", 'success');
        } else {
            $this->showAlert('All selected diagnoses are already saved.', 'info');
        }
    }

    private function createCustomDiagnosis()
    {
        $this->validate([
            'diagnosisType' => 'required|in:primary,secondary,differential',
            'diagnosisCertainty' => 'required|in:provisional,confirmed',
        ]);

        // Check for duplicate custom diagnosis
        $exists = EncounterAssessment::where('encounter_id', $this->encounter->id)
            ->where('custom_diagnosis', trim($this->customDiagnosis))
            ->exists();

        if ($exists) {
            $this->addError('customDiagnosis', 'This diagnosis is already added.');
            return;
        }

        EncounterAssessment::create([
            'encounter_id' => $this->encounter->id,
            'assessment_template_id' => null,
            'custom_diagnosis' => trim($this->customDiagnosis),
            'type' => $this->diagnosisType,
            'certainty' => $this->diagnosisCertainty,
            'notes' => $this->diagnosisNotes,
        ]);

        $this->resetForm();
        $this->loadDiagnoses();
        $this->showAlert('Custom diagnosis added successfully!', 'success');
    }

    private function updateSingleDiagnosis()
    {
        $this->validate([
            'diagnosisType' => 'required|in:primary,secondary,differential',
            'diagnosisCertainty' => 'required|in:provisional,confirmed',
        ]);

        $assessment = EncounterAssessment::find($this->editingId);
        $this->selectedTemplateIds = $this->ensureArray($this->selectedTemplateIds);

        if (!empty($this->selectedTemplateIds) && count($this->selectedTemplateIds) === 1) {
            $assessment->update([
                'assessment_template_id' => $this->selectedTemplateIds[0],
                'custom_diagnosis' => null,
                'type' => $this->diagnosisType,
                'certainty' => $this->diagnosisCertainty,
                'notes' => $this->diagnosisNotes,
            ]);
        } elseif (!empty($this->customDiagnosis)) {
            $assessment->update([
                'assessment_template_id' => null,
                'custom_diagnosis' => $this->customDiagnosis,
                'type' => $this->diagnosisType,
                'certainty' => $this->diagnosisCertainty,
                'notes' => $this->diagnosisNotes,
            ]);
        }

        $this->editingId = null;
        $this->resetForm();
        $this->loadDiagnoses();

        $this->showAlert('Diagnosis updated successfully!', 'success');
    }

    public function toggleTemplate($templateId)
    {
        $this->selectedTemplateIds = $this->ensureArray($this->selectedTemplateIds);

        if (in_array($templateId, $this->selectedTemplateIds)) {
            $this->selectedTemplateIds = array_diff($this->selectedTemplateIds, [$templateId]);
        } else {
            $this->selectedTemplateIds[] = (int)$templateId;
        }

        // Clear custom diagnosis when selecting templates
        if (!empty($this->selectedTemplateIds)) {
            $this->customDiagnosis = '';
        }
    }

    public function clearSelection()
    {
        $this->selectedTemplateIds = [];
        $this->customDiagnosis = '';
    }

    public function selectAllVisible()
    {
        $templates = $this->getFilteredTemplates();
        $templateIds = $templates->pluck('id')->toArray();

        // Add all visible templates to selection
        $this->selectedTemplateIds = array_unique(array_merge(
            $this->ensureArray($this->selectedTemplateIds),
            $templateIds
        ));

        $this->customDiagnosis = '';
    }

    public function editDiagnosis($id)
    {
        $assessment = EncounterAssessment::with('template')->findOrFail($id);

        $this->editingId = $id;
        $this->selectedTemplateIds = $assessment->assessment_template_id
            ? [(int)$assessment->assessment_template_id]
            : [];
        $this->customDiagnosis = $assessment->custom_diagnosis ?? '';
        $this->diagnosisType = $assessment->type;
        $this->diagnosisCertainty = $assessment->certainty;
        $this->diagnosisNotes = $assessment->notes;
    }

    public function deleteDiagnosis($id)
    {
        EncounterAssessment::findOrFail($id)->delete();
        $this->loadDiagnoses();
        $this->showAlert('Diagnosis removed!', 'success');
    }

    public function resetForm()
    {
        $this->selectedTemplateIds = [];
        $this->customDiagnosis = '';
        $this->diagnosisType = 'primary';
        $this->diagnosisCertainty = 'provisional';
        $this->diagnosisNotes = '';
        $this->searchTerm = '';
        $this->editingId = null;

        $this->resetErrorBag();
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function saveAndContinue()
    {
        // Validate form data
        $this->validate([
            'diagnosisType' => 'required|in:primary,secondary,differential',
            'diagnosisCertainty' => 'required|in:provisional,confirmed',
        ]);

        $this->selectedTemplateIds = $this->ensureArray($this->selectedTemplateIds);

        // Check if we have anything to save
        $hasDiagnosis = false;

        // Save selected templates if any
        if (!empty($this->selectedTemplateIds)) {
            foreach ($this->selectedTemplateIds as $templateId) {
                $exists = EncounterAssessment::where('encounter_id', $this->encounter->id)
                    ->where('assessment_template_id', $templateId)
                    ->exists();

                if (!$exists) {
                    EncounterAssessment::create([
                        'encounter_id' => $this->encounter->id,
                        'assessment_template_id' => $templateId,
                        'custom_diagnosis' => null,
                        'type' => $this->diagnosisType,
                        'certainty' => $this->diagnosisCertainty,
                        'notes' => $this->diagnosisNotes,
                    ]);
                    $hasDiagnosis = true;
                }
            }
        }

        // Save custom diagnosis if provided
        if (!empty(trim($this->customDiagnosis))) {
            $exists = EncounterAssessment::where('encounter_id', $this->encounter->id)
                ->where('custom_diagnosis', trim($this->customDiagnosis))
                ->exists();

            if (!$exists) {
                EncounterAssessment::create([
                    'encounter_id' => $this->encounter->id,
                    'assessment_template_id' => null,
                    'custom_diagnosis' => trim($this->customDiagnosis),
                    'type' => $this->diagnosisType,
                    'certainty' => $this->diagnosisCertainty,
                    'notes' => $this->diagnosisNotes,
                ]);
                $hasDiagnosis = true;
            }
        }

        // If we saved something, reset form
        if ($hasDiagnosis) {
            $this->resetForm();
            $this->loadDiagnoses();
            $this->showAlert('Assessment saved successfully!', 'success');
        } else {
            // If nothing was saved but form is complete, just show message
            if (empty($this->selectedTemplateIds) && empty($this->customDiagnosis)) {
                $this->showAlert('No diagnosis to save. Please add a diagnosis first.', 'info');
            } else {
                $this->showAlert('All selected diagnoses are already saved.', 'info');
            }
        }
    }

    public function skipAssessment()
    {
        return $this->redirect(route('lab-orders.create', $this->encounter), navigate: true);
    }
    public function skipImaging()
    {
        return $this->redirect(route('doctor.order-medication', $this->encounter), navigate: true);
    }
    public function orderReferral()
    {
        return $this->redirect(route('referrals.create', $this->encounter), navigate: true);
    }

    public function nextToOrders()
    {
        // Redirect to create lab order page for this encounter
        return $this->redirect(route('lab-orders.create', $this->encounter), navigate: true);
    }

    public function backToExamination()
    {
        return $this->redirect(route('consultation.examination', $this->encounter), navigate: true);
    }

    private function showAlert($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    private function ensureArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && !empty($value)) {
            // Try to decode JSON string
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            // If it's a comma-separated string
            if (str_contains($value, ',')) {
                return array_map('intval', explode(',', $value));
            }

            // Single value as string
            return [$value];
        }

        if (is_int($value) || is_float($value)) {
            return [$value];
        }

        return [];
    }

    public function getFilteredTemplates()
    {
        return AssessmentTemplate::where('active', true)
            ->when($this->searchTerm, function ($query) {
                $query->where('diagnosis', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('diagnosis')
            ->limit(15)
            ->get();
    }

    public function getSelectedTemplatesProperty(): Collection
    {
        $ids = $this->ensureArray($this->selectedTemplateIds);

        if (empty($ids)) {
            return collect();
        }

        // Ensure all IDs are integers
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, function ($id) {
            return is_int($id) && $id > 0;
        });

        if (empty($ids)) {
            return collect();
        }

        return AssessmentTemplate::whereIn('id', $ids)->get();
    }

    public function updatedSelectedTemplateIds($value)
    {
        // Ensure it's always an array after update
        $this->selectedTemplateIds = $this->ensureArray($value);
    }

    public function render()
    {
        return view('livewire.doctor.assessment-form', [
            'templates' => $this->getFilteredTemplates(),
            'selectedTemplates' => $this->selectedTemplates,
        ]);
    }
}
