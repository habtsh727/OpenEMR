<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabQuestionnaireAnswer;
use App\Models\RehabQuestionnaireTemplate;
use App\Models\RehabTemplateQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuestionnaireForm extends Component
{
    public RehabEncounter $rehabEncounter;
    public RehabQuestionnaireTemplate $template;
    
    // Form data
    public $answers = [];
    public $rehabNotes = '';
    public $currentStep = 1;
    public $totalSteps = 1;
    
    // Validation state
    public $errors = [];
    public $showConfirmModal = false;

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

            // Add type-specific validation
            switch ($question->type) {
                case 'boolean':
                    $rule[] = 'boolean';
                    $rule[] = 'in:0,1,true,false,yes,no';
                    break;
                case 'checkbox':
                    $rule[] = 'array';
                    break;
                case 'number':
                    $rule[] = 'numeric';
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
                $messages["answers.{$question->id}.value.required"] = "The {$question->question} field is required.";
            }
            
            switch ($question->type) {
                case 'boolean':
                    $messages["answers.{$question->id}.value.boolean"] = "Please select Yes or No for: {$question->question}";
                    $messages["answers.{$question->id}.value.in"] = "Please select Yes or No for: {$question->question}";
                    break;
                case 'checkbox':
                    $messages["answers.{$question->id}.value.array"] = "Please select at least one option for: {$question->question}";
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
            'answers'
        ])->findOrFail($id);

        // Get the default template
        $this->template = RehabQuestionnaireTemplate::with('questions')
            ->firstOrFail();

        $this->totalSteps = ceil($this->template->questions->count() / 5); // Group questions into steps

        // Initialize answers from existing data or create empty
        $this->initializeAnswers();

        // Set rehab notes if exists
        $this->rehabNotes = $this->rehabEncounter->rehab_notes ?? '';
    }

    protected function initializeAnswers()
    {
        foreach ($this->template->questions as $question) {
            $existingAnswer = $this->rehabEncounter->answers
                ->where('rehab_template_question_id', $question->id)
                ->first();

            if ($existingAnswer) {
                // Handle different answer formats
                $answerValue = $existingAnswer->answer;
                
                // Convert boolean values to proper format
                if ($question->type === 'boolean') {
                    if (is_bool($answerValue)) {
                        $answerValue = $answerValue ? '1' : '0';
                    } elseif (is_string($answerValue)) {
                        $answerValue = in_array(strtolower($answerValue), ['1', 'true', 'yes']) ? '1' : '0';
                    }
                }
                
                // Handle checkbox - ensure it's array
                if ($question->type === 'checkbox' && is_string($answerValue)) {
                    $answerValue = json_decode($answerValue, true) ?? [];
                }

                $this->answers[$question->id] = [
                    'id' => $existingAnswer->id,
                    'value' => $answerValue,
                    'note' => $existingAnswer->note,
                ];
            } else {
                $this->answers[$question->id] = [
                    'id' => null,
                    'value' => $question->type === 'checkbox' ? [] : '',
                    'note' => '',
                ];
            }
        }
    }

    public function updated($propertyName)
    {
        // Validate on update for required fields
        if (str_starts_with($propertyName, 'answers.')) {
            $this->validateOnly($propertyName);
        }
    }

    public function saveProgress()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                foreach ($this->answers as $questionId => $answerData) {
                    $question = RehabTemplateQuestion::find($questionId);
                    
                    // Format answer based on question type
                    $formattedAnswer = $this->formatAnswerForStorage($answerData['value'], $question->type);

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

                // Update rehab notes
                $this->rehabEncounter->update([
                    'rehab_notes' => $this->rehabNotes,
                    'status' => 'questionnaire_in_progress',
                    'questionnaire_filled_by' => auth()->id(),
                ]);
            });

            $this->dispatch('notify', [
                'message' => 'Progress saved successfully!',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to save rehab questionnaire: ' . $e->getMessage(), [
                'rehab_encounter_id' => $this->rehabEncounter->id,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', [
                'message' => 'Failed to save progress. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function submitQuestionnaire()
    {
        $this->validate();

        // Check if all required questions are answered
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
            $this->dispatch('notify', [
                'message' => 'Please answer all required questions before submitting.',
                'type' => 'error'
            ]);
            
            // Scroll to first missing question
            $firstMissingId = $missingRequired->first()->id;
            $this->dispatch('scroll-to-question', questionId: $firstMissingId);
            
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
                    $formattedAnswer = $this->formatAnswerForStorage($answerData['value'], $question->type);

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
                    'questionnaire_filled_by' => auth()->id(),
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

    protected function formatAnswerForStorage($value, $type)
    {
        if ($value === null || $value === '') {
            return null;
        }

        switch ($type) {
            case 'boolean':
                // Convert various boolean formats to standard boolean
                if (is_bool($value)) {
                    return $value;
                }
                if (is_string($value)) {
                    return in_array(strtolower($value), ['1', 'true', 'yes', 'on']);
                }
                return (bool) $value;

            case 'checkbox':
                // Ensure checkbox values are stored as JSON array
                return is_array($value) ? json_encode(array_values($value)) : json_encode([]);

            case 'datetime':
                // Ensure datetime is in proper format
                try {
                    return \Carbon\Carbon::parse($value)->toDateTimeString();
                } catch (\Exception $e) {
                    return $value;
                }

            default:
                return $value;
        }
    }

    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
            $this->dispatch('step-changed', step: $this->currentStep);
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('step-changed', step: $this->currentStep);
        }
    }

    public function getQuestionsForCurrentStepProperty()
    {
        $perStep = 5;
        $offset = ($this->currentStep - 1) * $perStep;
        
        return $this->template->questions
            ->slice($offset, $perStep)
            ->values();
    }

    public function render()
    {
        return view('livewire.rehab.questionnaire-form', [
            'questions' => $this->questionsForCurrentStep,
            'progress' => ($this->currentStep / $this->totalSteps) * 100,
        ]);
    }
}