<?php

namespace App\Livewire\Doctor;

use App\Models\Encounter;
use App\Models\AssessmentTemplate;
use App\Models\EncounterAssessment;
use Livewire\Component;

class AssessmentForm extends Component
{
    public $encounter;
    public $patient;
    
    // For template selection
    public $selectedTemplateId = '';
    public $searchTerm = '';
    
    // For custom diagnosis
    public $customDiagnosis = '';
    
    // For diagnosis details
    public $diagnosisType = 'primary';
    public $diagnosisCertainty = 'provisional';
    public $diagnosisNotes = '';
    
    // List of added diagnoses
    public $diagnoses = [];
    
    // For editing
    public $editingId = null;
    
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
        // Validate
        $diagnosis = $this->getSelectedDiagnosis();
        
        if (empty($diagnosis)) {
            $this->addError('diagnosis', 'Please select or enter a diagnosis');
            return;
        }
        
        // Check if editing existing
        if ($this->editingId) {
            $assessment = EncounterAssessment::find($this->editingId);
            
            if ($this->selectedTemplateId) {
                $assessment->update([
                    'assessment_template_id' => $this->selectedTemplateId,
                    'custom_diagnosis' => null,
                    'type' => $this->diagnosisType,
                    'certainty' => $this->diagnosisCertainty,
                    'notes' => $this->diagnosisNotes,
                ]);
            } else {
                $assessment->update([
                    'assessment_template_id' => null,
                    'custom_diagnosis' => $this->customDiagnosis,
                    'type' => $this->diagnosisType,
                    'certainty' => $this->diagnosisCertainty,
                    'notes' => $this->diagnosisNotes,
                ]);
            }
            
            $this->editingId = null;
        } else {
            // Create new assessment
            $data = [
                'encounter_id' => $this->encounter->id,
                'type' => $this->diagnosisType,
                'certainty' => $this->diagnosisCertainty,
                'notes' => $this->diagnosisNotes,
            ];
            
            if ($this->selectedTemplateId) {
                $data['assessment_template_id'] = $this->selectedTemplateId;
            } else {
                $data['custom_diagnosis'] = $this->customDiagnosis;
            }
            
            EncounterAssessment::create($data);
        }
        
        // Reset form
        $this->resetForm();
        
        // Reload diagnoses
        $this->loadDiagnoses();
        
        session()->flash('message', 'Diagnosis saved successfully!');
    }
    
    private function getSelectedDiagnosis()
    {
        if ($this->selectedTemplateId) {
            $template = AssessmentTemplate::find($this->selectedTemplateId);
            return $template ? $template->diagnosis : null;
        }
        
        return trim($this->customDiagnosis);
    }
    
    public function editDiagnosis($id)
    {
        $assessment = EncounterAssessment::with('template')->findOrFail($id);
        
        $this->editingId = $id;
        
        if ($assessment->assessment_template_id) {
            $this->selectedTemplateId = $assessment->assessment_template_id;
            $this->customDiagnosis = '';
        } else {
            $this->selectedTemplateId = '';
            $this->customDiagnosis = $assessment->custom_diagnosis;
        }
        
        $this->diagnosisType = $assessment->type;
        $this->diagnosisCertainty = $assessment->certainty;
        $this->diagnosisNotes = $assessment->notes;
    }
    
    public function deleteDiagnosis($id)
    {
        EncounterAssessment::findOrFail($id)->delete();
        $this->loadDiagnoses();
        session()->flash('message', 'Diagnosis removed!');
    }
    
    public function resetForm()
    {
        $this->selectedTemplateId = '';
        $this->customDiagnosis = '';
        $this->diagnosisType = 'primary';
        $this->diagnosisCertainty = 'provisional';
        $this->diagnosisNotes = '';
        $this->searchTerm = '';
        $this->editingId = null;
    }
    
    public function cancelEdit()
    {
        $this->resetForm();
    }
    
    public function skipAssessment()
    {
        // Just redirect to next step (orders) without saving anything
        return $this->redirect(route('consultation.orders', $this->encounter), navigate: true);
    }
    
    public function nextToOrders()
    {
        // Save any pending changes and go to orders
        return $this->redirect(route('consultation.orders', $this->encounter), navigate: true);
    }
    
    public function backToExamination()
    {
        return $this->redirect(route('consultation.examination', $this->encounter), navigate: true);
    }
    
    public function completeConsultation()
    {
        // Update encounter status
        $this->encounter->update([
            'status' => 'completed',
            'processed_by' => auth()->id(),
        ]);
        
        session()->flash('success', 'Consultation completed successfully!');
        return $this->redirect(route('doctor.queue'), navigate: true);
    }
    
    public function getFilteredTemplates()
    {
        return AssessmentTemplate::where('active', true)
            ->when($this->searchTerm, function ($query) {
                $query->where('diagnosis', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('diagnosis')
            ->limit(10)
            ->get();
    }
    public function render()
    {
        return view('livewire.doctor.assessment-form', [
            'templates' => $this->getFilteredTemplates()
        ]);
    }
}
