<?php

namespace App\Livewire\Rehab\Template;

use App\Models\RehabQuestionnaireTemplate;
use App\Models\RehabTemplateQuestion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Questions extends Component
{
    public RehabQuestionnaireTemplate $template;
    public $questions = [];
    
    // Form state
    public $showForm = false;
    public $editingQuestionId = null;
    
    // Question fields
    public $question = '';
    public $type = 'text';
    public $options = '';
    public $is_required = false;
    public $order = 0;

    protected $rules = [
        'question' => 'required|string|max:65535',
        'type' => 'required|in:boolean,checkbox,text,textarea,number,datetime,select',
        'options' => 'nullable|string|max:65535',
        'is_required' => 'boolean',
        'order' => 'integer|min:0',
    ];

    protected function messages()
    {
        return [
            'question.required' => 'The question text is required.',
            'type.required' => 'Please select a question type.',
            'type.in' => 'Invalid question type selected.',
        ];
    }

    public function mount($id)
    {
        $this->template = RehabQuestionnaireTemplate::with('questions')
            ->findOrFail($id);
        
        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $this->questions = $this->template->questions()
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    public function createQuestion()
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editingQuestionId = null;
        
        // Set default order to last + 1
        $this->order = $this->template->questions()->max('order') + 1;
    }

    public function editQuestion($id)
    {
        $question = RehabTemplateQuestion::findOrFail($id);
        
        $this->editingQuestionId = $question->id;
        $this->question = $question->question;
        $this->type = $question->type;
        $this->options = is_array($question->options) 
            ? implode("\n", $question->options) 
            : ($question->options ?? '');
        $this->is_required = $question->is_required;
        $this->order = $question->order;
        
        $this->showForm = true;
    }

    public function saveQuestion()
    {
        $this->validate();

        // Validate options for select and checkbox
        if (in_array($this->type, ['select', 'checkbox']) && empty(trim($this->options))) {
            $this->addError('options', 'Options are required for ' . $this->type . ' type.');
            return;
        }

        try {
            DB::transaction(function () {
                $optionsArray = null;
                if (in_array($this->type, ['select', 'checkbox']) && !empty($this->options)) {
                    // Split by new line and clean up
                    $optionsArray = array_values(array_filter(
                        array_map('trim', explode("\n", $this->options))
                    ));
                }

                $data = [
                    'rehab_questionnaire_template_id' => $this->template->id,
                    'question' => $this->question,
                    'type' => $this->type,
                    'options' => $optionsArray ? json_encode($optionsArray) : null,
                    'is_required' => $this->is_required,
                    'order' => $this->order,
                ];

                if ($this->editingQuestionId) {
                    $question = RehabTemplateQuestion::find($this->editingQuestionId);
                    $question->update($data);
                    $message = 'Question updated successfully!';
                } else {
                    RehabTemplateQuestion::create($data);
                    $message = 'Question added successfully!';
                }
            });

            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);

            $this->resetForm();
            $this->showForm = false;
            $this->loadQuestions();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to save question. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function deleteQuestion($id)
    {
        try {
            $question = RehabTemplateQuestion::findOrFail($id);
            $question->delete();

            $this->dispatch('notify', [
                'message' => 'Question deleted successfully!',
                'type' => 'success'
            ]);

            $this->loadQuestions();

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to delete question.',
                'type' => 'error'
            ]);
        }
    }

    public function moveUp($id)
    {
        $current = RehabTemplateQuestion::findOrFail($id);
        $previous = RehabTemplateQuestion::where('rehab_questionnaire_template_id', $this->template->id)
            ->where('order', '<', $current->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previous) {
            $tempOrder = $current->order;
            $current->update(['order' => $previous->order]);
            $previous->update(['order' => $tempOrder]);
            $this->loadQuestions();
        }
    }

    public function moveDown($id)
    {
        $current = RehabTemplateQuestion::findOrFail($id);
        $next = RehabTemplateQuestion::where('rehab_questionnaire_template_id', $this->template->id)
            ->where('order', '>', $current->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($next) {
            $tempOrder = $current->order;
            $current->update(['order' => $next->order]);
            $next->update(['order' => $tempOrder]);
            $this->loadQuestions();
        }
    }

    public function resetForm()
    {
        $this->reset(['question', 'type', 'options', 'is_required', 'order']);
        $this->resetErrorBag();
        $this->type = 'text';
        $this->is_required = false;
    }

    public function cancelForm()
    {
        $this->resetForm();
        $this->showForm = false;
        $this->editingQuestionId = null;
    }

    public function getQuestionTypeOptionsProperty()
    {
        return [
            'text' => 'Short Text',
            'textarea' => 'Long Text (Textarea)',
            'number' => 'Number',
            'boolean' => 'Yes/No',
            'checkbox' => 'Checkbox (Multiple)',
            'select' => 'Dropdown Select',
            'datetime' => 'Date & Time',
        ];
    }

    public function render()
    {
        return view('livewire.rehab.template.questions', [
            'typeOptions' => $this->questionTypeOptions,
        ]);
    }
}