<?php

namespace App\Livewire\Config;

use App\Models\ExaminationTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class ExaminationTemplates extends Component
{
    use WithPagination;

    public $system;
    public $name;
    public $field_type = 'text';
    public $options = '';
    public $active = true;

    public $systemFilter = null;
    public $fieldTypeFilter = null;
    public $activeFilter = null;
    public $search = '';

    public $templateId;
    public $isEditing = false;
    public $showForm = false;

    protected $rules = [
        'system' => 'required|string|min:2|max:100',
        'name' => 'required|string|min:2|max:255',
        'field_type' => 'required|in:text,number,yes_no,select',
        'options' => 'nullable|string',
        'active' => 'boolean'
    ];

    public function render()
    {
        $templates = ExaminationTemplate::query()
            ->when($this->activeFilter != null, function ($query) {
                $query->where('active', $this->activeFilter);
            })
            ->when($this->fieldTypeFilter, function ($query) {
                $query->where('field_type', $this->fieldTypeFilter);
            })
            ->when($this->systemFilter, function ($query) {
                $query->where('system', $this->systemFilter);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('system', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        // Get unique systems for filter dropdown
        $systems = ExaminationTemplate::select('system')->distinct()->pluck('system');

        return view('livewire.config.examination-templates', compact('templates', 'systems'));
    }
    // In ExaminationTemplates.php Livewire component
    public function getFormattedOptions($options)
    {
        if (empty($options)) {
            return 'No options';
        }

        if (is_array($options)) {
            return implode(', ', $options);
        }

        if (is_string($options)) {
            $decoded = json_decode($options, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return implode(', ', $decoded);
            }
            return $options;
        }

        return 'Invalid options format';
    }
    public function create()
    {
        $this->validate();

        $data = [
            'system' => $this->system,
            'name' => $this->name,
            'field_type' => $this->field_type,
            'active' => $this->active
        ];

        // Parse options if field_type is select
        if ($this->field_type === 'select' && !empty($this->options)) {
            $optionsArray = array_map('trim', explode(',', $this->options));
            $data['options'] = $optionsArray;
        }

        ExaminationTemplate::create($data);

        $this->resetForm();
        session()->flash('success', 'Examination template created successfully!');
    }

    public function edit($id)
    {
        $template = ExaminationTemplate::findOrFail($id);
        $this->templateId = $id;
        $this->system = $template->system;
        $this->name = $template->name;
        $this->field_type = $template->field_type;
        $this->active = $template->active;

        // Convert options array to comma-separated string for editing
        if ($template->field_type === 'select' && $template->options) {
            $this->options = implode(', ', $template->options);
        }

        $this->isEditing = true;
        $this->showForm = true;
    }

    public function update()
    {
        $this->validate();

        $template = ExaminationTemplate::findOrFail($this->templateId);

        $data = [
            'system' => $this->system,
            'name' => $this->name,
            'field_type' => $this->field_type,
            'active' => $this->active
        ];

        // Parse options if field_type is select
        if ($this->field_type === 'select' && !empty($this->options)) {
            $optionsArray = array_map('trim', explode(',', $this->options));
            $data['options'] = $optionsArray;
        } else {
            $data['options'] = null;
        }

        $template->update($data);

        $this->resetForm();
        session()->flash('success', 'Examination template updated successfully!');
    }

    public function delete($id)
    {
        ExaminationTemplate::findOrFail($id)->delete();
        session()->flash('success', 'Examination template deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $template = ExaminationTemplate::findOrFail($id);
        $template->update(['active' => !$template->active]);
        session()->flash('success', 'Template status updated!');
    }

    // Reset form method
    public function resetForm()
    {
        $this->reset(['system', 'name', 'field_type', 'options', 'active', 'templateId', 'isEditing', 'showForm']);
        $this->active = true;
        $this->field_type = 'text';
    }

    // Dynamic update when field_type changes
    public function updatedFieldType($value)
    {
        if ($value != 'select') {
            $this->options = '';
        }
    }
}
