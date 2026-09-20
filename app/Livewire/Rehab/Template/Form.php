<?php

namespace App\Livewire\Rehab\Template;

use App\Models\RehabQuestionnaireTemplate;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Form extends Component
{
    public RehabQuestionnaireTemplate $template;
    public $title = '';
    public $isEditing = false;

    protected $rules = [
        'title' => 'required|string|max:255|unique:rehab_questionnaire_templates,title',
    ];

    protected function messages()
    {
        return [
            'title.required' => 'The template title is required.',
            'title.unique' => 'A template with this title already exists.',
            'title.max' => 'The title must not exceed 255 characters.',
        ];
    }

    public function mount($id = null)
    {
        $this->isEditing = $id !== null;

        if ($this->isEditing) {
            $this->template = RehabQuestionnaireTemplate::findOrFail($id);
            $this->title = $this->template->title;
            
            // Update rules for editing - ignore current ID
            $this->rules['title'] = 'required|string|max:255|unique:rehab_questionnaire_templates,title,' . $this->template->id;
        } else {
            $this->template = new RehabQuestionnaireTemplate();
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                if ($this->isEditing) {
                    $this->template->update([
                        'title' => $this->title,
                    ]);
                    $message = 'Template updated successfully!';
                } else {
                    $this->template = RehabQuestionnaireTemplate::create([
                        'title' => $this->title,
                    ]);
                    $message = 'Template created successfully!';
                }
            });

            $this->dispatch('notify', [
                'message' => $message,
                'type' => 'success'
            ]);

            return $this->redirect(route('rehab.templates.index'), navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Failed to save template. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function cancel()
    {
        return $this->redirect(route('rehab.templates.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.rehab.template.form', [
            'pageTitle' => $this->isEditing ? 'Edit Template' : 'Create New Template'
        ]);
    }
}