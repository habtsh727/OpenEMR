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
    public $template; // Changed from RehabQuestionnaireTemplate to allow virtual template

    // Form data
    public $answers = [];
    public $rehabNotes = '';

    // UI state
    public $showConfirmModal = false;
    public $lastSaved = null;

    // Track which questions belong to which template (for organization)
    public $questionsByTemplate = [];

    // Track collapsed sections
    public $collapsedSections = [];

    protected function rules()
    {
        $rules = [];

        foreach ($this->template['questions'] as $question) {
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

        foreach ($this->template['questions'] as $question) {
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
        // Load the rehab encounter with relationships
        $this->rehabEncounter = RehabEncounter::with([
            'encounter.patient',
            'encounter.doctor',
            'answers.question'
        ])->findOrFail($id);

        // Security check - only assigned staff can edit
        if ($this->rehabEncounter->questionnaire_filled_by &&
            $this->rehabEncounter->questionnaire_filled_by != auth()->id()) {
            abort(403, 'This questionnaire is being filled by another staff member.');
        }

        // Load ALL templates with their questions
        $allTemplates = RehabQuestionnaireTemplate::with('questions')
            ->orderBy('title')
            ->get();

        // Check if there are any templates
        if ($allTemplates->isEmpty()) {
            abort(404, 'No questionnaire templates found. Please create a template first.');
        }

        // Combine all questions from all templates
        $allQuestions = collect();
        $this->questionsByTemplate = [];

        foreach ($allTemplates as $template) {
            $this->questionsByTemplate[$template->id] = [
                'title' => $template->title,
                'questions' => $template->questions->sortBy('order'),
                'total_questions' => $template->questions->count(),
                'answered_count' => 0 // Will calculate later
            ];
            $allQuestions = $allQuestions->concat($template->questions);
        }

        // Create a virtual template structure to hold all questions
        $this->template = [
            'id' => 0, // Virtual ID for all templates combined
            'title' => 'Complete Rehabilitation Assessment',
            'questions' => $allQuestions->sortBy(function($question) {
                // Sort by template order first, then by question order
                return $question->order;
            }),
            'templates_count' => $allTemplates->count(),
            'total_questions' => $allQuestions->count()
        ];

        // Initialize collapsed sections (all expanded by default)
        foreach ($this->questionsByTemplate as $templateId => $templateData) {
            // Check if there's a saved state in session
            $this->collapsedSections[$templateId] = session()->get("collapsed_section_{$templateId}", false);
        }

        // Log for debugging
        \Log::info('Loaded all questions from templates', [
            'total_templates' => $allTemplates->count(),
            'total_questions' => $allQuestions->count(),
            'templates' => $allTemplates->pluck('title')->toArray()
        ]);

        // Initialize answers
        $this->initializeAnswers();

        // Set rehab notes
        $this->rehabNotes = $this->rehabEncounter->rehab_notes ?? '';
    }

    private function initializeAnswers()
    {
        // Reset answers array
        $this->answers = [];

        // Get existing answers for this encounter
        $existingAnswers = $this->rehabEncounter->answers
            ->keyBy('rehab_template_question_id');

        // Initialize answers for all questions
        foreach ($this->template['questions'] as $question) {
            $existingAnswer = $existingAnswers->get($question->id);

            if ($existingAnswer) {
                // Format existing answer based on type
                $value = $this->formatAnswerForDisplay($existingAnswer->answer, $question->type);

                $this->answers[$question->id] = [
                    'id' => $existingAnswer->id,
                    'value' => $value,
                    'note' => $existingAnswer->note,
                    'template_id' => $question->rehab_questionnaire_template_id
                ];

                // Update answered count for this template if answer is not empty
                if (!empty($value) && $value !== '' && $value !== []) {
                    $templateId = $question->rehab_questionnaire_template_id;
                    if (isset($this->questionsByTemplate[$templateId])) {
                        $this->questionsByTemplate[$templateId]['answered_count']++;
                    }
                }
            } else {
                // Initialize empty answer
                $this->answers[$question->id] = [
                    'id' => null,
                    'value' => $question->type === 'checkbox' ? [] : '',
                    'note' => '',
                    'template_id' => $question->rehab_questionnaire_template_id
                ];
            }
        }

        // Debug logging
        \Log::info('Answers initialized', [
            'total_questions' => $this->template['questions']->count(),
            'answers_initialized' => count($this->answers),
            'existing_answers_found' => $existingAnswers->count()
        ]);
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

    public function toggleSection($templateId)
    {
        $this->collapsedSections[$templateId] = !$this->collapsedSections[$templateId];

        // Save state to session to persist across page refreshes
        session()->put("collapsed_section_{$templateId}", $this->collapsedSections[$templateId]);
    }

    public function expandAll()
    {
        foreach ($this->questionsByTemplate as $templateId => $templateData) {
            $this->collapsedSections[$templateId] = false;
            session()->put("collapsed_section_{$templateId}", false);
        }

        $this->dispatch('notify', 'All sections expanded', 'success');
    }

    public function collapseAll()
    {
        foreach ($this->questionsByTemplate as $templateId => $templateData) {
            $this->collapsedSections[$templateId] = true;
            session()->put("collapsed_section_{$templateId}", true);
        }

        $this->dispatch('notify', 'All sections collapsed', 'success');
    }

    public function expandUnansweredRequired()
    {
        foreach ($this->questionsByTemplate as $templateId => $templateData) {
            $hasUnansweredRequired = false;

            foreach ($templateData['questions'] as $question) {
                if ($question->is_required) {
                    $answer = $this->answers[$question->id]['value'] ?? null;
                    if (empty($answer) || $answer === '' || $answer === []) {
                        $hasUnansweredRequired = true;
                        break;
                    }
                }
            }

            // Expand if has unanswered required questions
            $this->collapsedSections[$templateId] = !$hasUnansweredRequired;
            session()->put("collapsed_section_{$templateId}", $this->collapsedSections[$templateId]);
        }

        $this->dispatch('notify', 'Sections with unanswered required questions expanded', 'success');
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
        $missingRequired = collect($this->template['questions'])
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
            $this->dispatch('notify', 'Please answer all required questions before submitting.', 'error');
            $this->dispatch('scroll-to-question', questionId: $firstMissing->id);
            return;
        }

        $this->showConfirmModal = true;
    }

    public function confirmSubmit()
    {
        try {
            DB::transaction(function () {
                // Save all answers
                foreach ($this->answers as $questionId => $answerData) {
                    $question = RehabTemplateQuestion::find($questionId);

                    if (!$question) {
                        continue;
                    }

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
                    'questionnaire_filled_by' => auth()->id(),
                ]);
            });

            $this->showConfirmModal = false;

            $this->dispatch('notify', '✅ Questionnaire submitted successfully! The doctor will review it shortly.', 'success');

            // Redirect to queue after 2 seconds
            $this->dispatch('redirect-to-queue');

        } catch (\Exception $e) {
            \Log::error('Failed to submit rehab questionnaire: ' . $e->getMessage(), [
                'rehab_encounter_id' => $this->rehabEncounter->id,
                'error' => $e->getMessage()
            ]);

            $this->dispatch('notify', '❌ Failed to submit questionnaire. Please try again.', 'error');
            $this->showConfirmModal = false;
        }
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
            'questions' => $this->template['questions']->sortBy('order'),
            'questionsByTemplate' => $this->questionsByTemplate,
            'collapsedSections' => $this->collapsedSections,
            'patient' => $patient,
            'doctor' => $this->rehabEncounter->encounter->doctor,
            'totalTemplates' => $this->template['templates_count'],
            'totalQuestions' => $this->template['total_questions']
        ]);
    }
}
