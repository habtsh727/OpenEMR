<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabQuestionnaireAnswer;
use App\Models\RehabQuestionnaireTemplate;
use App\Models\RehabTemplateQuestion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuestionnaireForm extends Component
{
    public RehabEncounter $rehabEncounter;
    public RehabQuestionnaireTemplate $template;
    
    // Form data
    public $answers = [];
    public $rehabNotes = '';
    
    // UI state
    public $showConfirmModal = false;
    public $autoSaveEnabled = true;
    public $lastSaved = null;

    protected function rules()
    {
        $rules = [];
        
        foreach ($this->template->questions as $question) {
            $rule = [];
            
            if ($question->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            // Type-specific validation
            switch ($question->type) {
                case 'boolean':
                    $rule[] = 'in:0,1,true,false,yes,no';
                    break;
                case 'checkbox':
                    $rule[] = 'array';
                    $rule[] = 'min:1';
                    break;
                case 'number':
                    $rule[] = 'numeric';
                    $rule[] = 'min:0';
                    $rule[] = 'max:999999';
                    break;
                case 'datetime':
                    $rule[] = 'date';
                    break;
                case 'text':
                case 'textarea':
                case 'select':
                    $rule[] = 'string';
                    $rule[] = 'max:65535';
                    break;
            }

            $rules["answers.{$question->id}.value"] = $rule;
            $rules["answers.{$question->id}.note"] = 'nullable|string|max:1000';
        }

        return $rules;
    }

    protected function messages()
    {
        $messages = [];
        
        foreach ($this->template->questions as $question) {
            if ($question->is_required) {
                $messages["answers.{$question->id}.value.required"] = "Please answer: {$question->question}";
            }
            
            switch ($question->type) {
                case 'boolean':
                    $messages["answers.{$question->id}.value.in"] = "Please select Yes or No for: {$question->question}";
                    break;
                case 'checkbox':
                    $messages["answers.{$question->id}.value.min"] = "Please select at least one option for: {$question->question}";
                    break;
                case 'number':
                    $messages["answers.{$question->id}.value.numeric"] = "Please enter a valid number for: {$question->question}";
                    break;
                case 'datetime':
                    $messages["answers.{$question->id}.value.date"] = "Please enter a valid date and time for: {$question->question}";
                    break;
            }
        }

        return $messages;
    }

    public function mount($id)
    {
        $this->rehabEncounter = RehabEncounter::with([
            'encounter.patient',
            'encounter.doctor',
            'answers.question'
        ])->findOrFail($id);

        // Security check - only assigned staff can edit
        if ($this->rehabEncounter->questionnaire_filled_by && 
            $this->rehabEncounter->questionnaire_filled_by !== auth()->id()) {
            abort(403, 'This questionnaire is being filled by another staff member.');
        }

        // Get the default template
        $this->template = RehabQuestionnaireTemplate::with('questions')
            ->firstOrFail();

        // Initialize answers
        $this->initializeAnswers();
        
        // Set rehab notes
        $this->rehabNotes = $this->rehabEncounter->rehab_notes ?? '';
    }

    private function initializeAnswers()
    {
        foreach ($this->template->questions->sortBy('order') as $question) {
            $existingAnswer = $this->rehabEncounter->answers
                ->where('rehab_template_question_id', $question->id)
                ->first();

            if ($existingAnswer) {
                // Format existing answer based on type
                $value = $this->formatAnswerForDisplay($existingAnswer->answer, $question->type);
                
                $this->answers[$question->id] = [
                    'id' => $existingAnswer->id,
                    'value' => $value,
                    'note' => $existingAnswer->note,
                ];
            } else {
                // Initialize empty answer
                $this->answers[$question->id] = [
                    'id' => null,
                    'value' => $question->type === 'checkbox' ? [] : '',
                    'note' => '',
                ];
            }
        }
    }

    private function formatAnswerForDisplay($answer, $type)
    {
        if ($answer === null) {
            return $type === 'checkbox' ? [] : '';
        }

        switch ($type) {
            case 'boolean':
                if (is_bool($answer)) {
                    return $answer ? '1' : '0';
                }
                if (is_string($answer)) {
                    return in_array(strtolower($answer), ['1', 'true', 'yes']) ? '1' : '0';
                }
                return (string) $answer;
            
            case 'checkbox':
                if (is_array($answer)) {
                    return $answer;
                }
                if (is_string($answer)) {
                    return json_decode($answer, true) ?? [];
                }
                return [];
            
            case 'datetime':
                if ($answer instanceof \Carbon\Carbon) {
                    return $answer->format('Y-m-d\TH:i');
                }
                if (is_string($answer)) {
                    try {
                        return \Carbon\Carbon::parse($answer)->format('Y-m-d\TH:i');
                    } catch (\Exception $e) {
                        return '';
                    }
                }
                return '';
            
            default:
                return (string) $answer;
        }
    }

    public function updated($propertyName)
    {
        // Auto-save when answers change
        if (str_starts_with($propertyName, 'answers.') && $this->autoSaveEnabled) {
            $this->saveProgress(showNotification: false);
        }
    }

    public function saveProgress($showNotification = true)
    {
        // Partial validation - only validate filled fields
        $this->validate();

        try {
            DB::transaction(function () {
                foreach ($this->answers as $questionId => $answerData) {
                    $question = RehabTemplateQuestion::find($questionId);
                    
                    // Format answer for storage
                    $formattedAnswer = $this->formatAnswerForStorage(
                        $answerData['value'], 
                        $question->type
                    );

                    RehabQuestionnaireAnswer::updateOrCreate(
                        [
                            'rehab_encounter_id' => $this->rehabEncounter->id,
                            'rehab_template_question_id' => $questionId,
                        ],
                        [
                            'answer' => $formattedAnswer,
                            'note' => $answerData['note'] ?? null,
                        ]
                    );
                }

                // Update rehab encounter
                $this->rehabEncounter->update([
                    'rehab_notes' => $this->rehabNotes,
                    'status' => 'questionnaire_in_progress',
                    'questionnaire_filled_by' => auth()->id(),
                ]);
            });

            $this->lastSaved = now();

            if ($showNotification) {
                $this->dispatch('notify', [
                    'message' => 'Progress saved successfully!',
                    'type' => 'success'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Failed to save rehab questionnaire: ' . $e->getMessage(), [
                'rehab_encounter_id' => $this->rehabEncounter->id,
                'error' => $e->getMessage()
            ]);

            if ($showNotification) {
                $this->dispatch('notify', [
                    'message' => 'Failed to save progress. Please try again.',
                    'type' => 'error'
                ]);
            }
        }
    }

    private function formatAnswerForStorage($value, $type)
    {
        if ($value === null || $value === '') {
            return null;
        }

        switch ($type) {
            case 'boolean':
                if (is_bool($value)) {
                    return $value;
                }
                if (is_string($value)) {
                    return in_array(strtolower($value), ['1', 'true', 'yes', 'on']);
                }
                return (bool) $value;

            case 'checkbox':
                return is_array($value) ? json_encode(array_values($value)) : json_encode([]);

            case 'datetime':
                try {
                    return \Carbon\Carbon::parse($value)->toDateTimeString();
                } catch (\Exception $e) {
                    return $value;
                }

            default:
                return $value;
        }
    }

    public function submitQuestionnaire()
    {
        // Full validation before submission
        $this->validate();

        // Double-check all required questions are answered
        $missingRequired = $this->template->questions
            ->filter(function ($question) {
                if (!$question->is_required) return false;
                
                $answer = $this->answers[$question->id]['value'] ?? null;
                
                if ($question->type === 'checkbox') {
                    return empty($answer);
                }
                
                return $answer === null || $answer === '' || $answer === [];
            });

        if ($missingRequired->isNotEmpty()) {
            $firstMissing = $missingRequired->first();
            $this->dispatch('notify', [
                'message' => 'Please answer all required questions before submitting.',
                'type' => 'error'
            ]);
            $this->dispatch('scroll-to-question', questionId: $firstMissing->id);
            return;
        }

        $this->showConfirmModal = true;
    }

    public function confirmSubmit()
    {
        try {
            DB::transaction(function () {
                // Save all answers first
                foreach ($this->answers as $questionId => $answerData) {
                    $question = RehabTemplateQuestion::find($questionId);
                    $formattedAnswer = $this->formatAnswerForStorage(
                        $answerData['value'], 
                        $question->type
                    );

                    RehabQuestionnaireAnswer::updateOrCreate(
                        [
                            'rehab_encounter_id' => $this->rehabEncounter->id,
                            'rehab_template_question_id' => $questionId,
                        ],
                        [
                            'answer' => $formattedAnswer,
                            'note' => $answerData['note'] ?? null,
                        ]
                    );
                }

                // Update status to submitted_to_doctor
                $this->rehabEncounter->update([
                    'rehab_notes' => $this->rehabNotes,
                    'status' => 'submitted_to_doctor',
                ]);
            });

            $this->showConfirmModal = false;

            $this->dispatch('notify', [
                'message' => '✅ Questionnaire submitted successfully! The doctor will review it shortly.',
                'type' => 'success'
            ]);

            // Redirect to queue after 2 seconds
            $this->dispatch('redirect-to-queue');

        } catch (\Exception $e) {
            \Log::error('Failed to submit rehab questionnaire: ' . $e->getMessage(), [
                'rehab_encounter_id' => $this->rehabEncounter->id,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'message' => '❌ Failed to submit questionnaire. Please try again.',
                'type' => 'error'
            ]);
            
            $this->showConfirmModal = false;
        }
    }

    public function toggleAutoSave()
    {
        $this->autoSaveEnabled = !$this->autoSaveEnabled;
        
        $this->dispatch('notify', [
            'message' => $this->autoSaveEnabled ? 'Auto-save enabled' : 'Auto-save disabled',
            'type' => 'info'
        ]);
    }

    public function render()
    {
        // Get patient and ensure date_of_birth is a Carbon instance
        $patient = $this->rehabEncounter->encounter->patient;
        
        // Parse date_of_birth if it's a string
        if ($patient && is_string($patient->date_of_birth)) {
            try {
                $patient->date_of_birth = \Carbon\Carbon::parse($patient->date_of_birth);
            } catch (\Exception $e) {
                $patient->date_of_birth = null;
            }
        }

        return view('livewire.rehab.questionnaire-form', [
            'questions' => $this->template->questions->sortBy('order'),
            'patient' => $patient,
            'doctor' => $this->rehabEncounter->encounter->doctor,
        ]);
    }
}