<?php
// app/Livewire/Shared/DynamicQuestionnaire.php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\RehabQuestionnaireTemplate;
use App\Models\RehabTemplateQuestion;
use App\Models\RehabQuestionnaireAnswer;

class DynamicQuestionnaire extends Component
{
    public $rehabEncounter;
    public $template;
    public $questions;
    public $answers = [];
    public $notes = [];
    public $rehabNotes = '';
    public $isSubmitting = false;

    protected $listeners = ['refreshQuestionnaire' => '$refresh'];

    public function mount($rehabEncounter)
    {
        $this->rehabEncounter = $rehabEncounter;
        $this->template = $rehabEncounter->template ?? RehabQuestionnaireTemplate::first();
        $this->questions = $this->template->questions ?? collect();
        $this->rehabNotes = $rehabEncounter->rehab_notes ?? '';
        
        // Load existing answers
        $existingAnswers = $rehabEncounter->answers->keyBy('rehab_template_question_id');
        
        foreach ($this->questions as $question) {
            $existingAnswer = $existingAnswers->get($question->id);
            
            if ($existingAnswer) {
                $this->answers[$question->id] = $existingAnswer->answer;
                $this->notes[$question->id] = $existingAnswer->note ?? '';
            } else {
                // Set default values based on type
                $this->answers[$question->id] = match($question->type) {
                    'boolean' => false,
                    'checkbox' => [],
                    'number' => 0,
                    default => ''
                };
                $this->notes[$question->id] = '';
            }
        }
    }

    public function saveProgress()
    {
        $this->validate();

        foreach ($this->questions as $question) {
            $answer = RehabQuestionnaireAnswer::updateOrCreate(
                [
                    'rehab_encounter_id' => $this->rehabEncounter->id,
                    'rehab_template_question_id' => $question->id,
                ],
                [
                    'answer' => $this->answers[$question->id] ?? null,
                    'note' => $this->notes[$question->id] ?? null,
                ]
            );
        }

        // Update rehab notes
        $this->rehabEncounter->update([
            'rehab_notes' => $this->rehabNotes,
            'status' => 'questionnaire_in_progress'
        ]);

        $this->dispatch('notify', 
            type: 'success',
            message: 'Progress saved successfully!'
        );
    }

    public function submitToDoctor()
    {
        $this->validate();

        // Save all answers
        foreach ($this->questions as $question) {
            if ($question->is_required && empty($this->answers[$question->id])) {
                $this->addError("answers.{$question->id}", "This question is required.");
                return;
            }

            RehabQuestionnaireAnswer::updateOrCreate(
                [
                    'rehab_encounter_id' => $this->rehabEncounter->id,
                    'rehab_template_question_id' => $question->id,
                ],
                [
                    'answer' => $this->answers[$question->id] ?? null,
                    'note' => $this->notes[$question->id] ?? null,
                ]
            );
        }

        // Update status
        $this->rehabEncounter->update([
            'status' => 'submitted_to_doctor',
            'rehab_notes' => $this->rehabNotes,
            'questionnaire_filled_by' => auth()->id(),
            'submitted_at' => now(),
        ]);

        $this->dispatch('notify', 
            type: 'success',
            message: 'Questionnaire submitted to doctor!'
        );

        $this->redirect(route('rehab-staff.queue'));
    }

    public function render()
    {
        return view('livewire.shared.dynamic-questionnaire');
    }

    protected function rules()
    {
        $rules = [];

        foreach ($this->questions as $question) {
            if ($question->is_required) {
                $rules["answers.{$question->id}"] = 'required';
            }

            // Type-specific validation
            switch ($question->type) {
                case 'number':
                    $rules["answers.{$question->id}"] = 'nullable|numeric';
                    break;
                case 'datetime':
                    $rules["answers.{$question->id}"] = 'nullable|date';
                    break;
                case 'boolean':
                    $rules["answers.{$question->id}"] = 'nullable|boolean';
                    break;
            }
        }

        return $rules;
    }
}