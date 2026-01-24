<?php

namespace App\Livewire\Config;

use App\Models\MedicalHistoryTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class MedicalHistoryTemplates extends Component
{
    use WithPagination;
    
    public $name;
    public $field_type = 'yes_no';
    public $is_active = true;
    public $templateId;
    public $isEditing = false;
    public $showForm = false;
    public $filterActive = null;
    public $search = '';
    
    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'field_type' => 'required|in:yes_no,text,number,date',
        'is_active' => 'boolean'
    ];
    
    public function render()
    {
        $templates = MedicalHistoryTemplate::query()
            ->when($this->filterActive !== null, function ($query) {
                $query->where('is_active', $this->filterActive);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        return view('livewire.config.medical-history-templates', compact('templates'));
    }
    
    public function create()
    {
        $this->validate();
        
        MedicalHistoryTemplate::create([
            'name' => $this->name,
            'field_type' => $this->field_type,
            'is_active' => $this->is_active
        ]);
        
        $this->resetForm();
        session()->flash('success', 'Medical history template created successfully!');
    }
    
    public function edit($id)
    {
        $template = MedicalHistoryTemplate::findOrFail($id);
        $this->templateId = $id;
        $this->name = $template->name;
        $this->field_type = $template->field_type;
        $this->is_active = $template->is_active;
        $this->isEditing = true;
        $this->showForm = true;
    }
    
    public function update()
    {
        $this->validate();
        
        $template = MedicalHistoryTemplate::findOrFail($this->templateId);
        $template->update([
            'name' => $this->name,
            'field_type' => $this->field_type,
            'is_active' => $this->is_active
        ]);
        
        $this->resetForm();
        session()->flash('success', 'Medical history template updated successfully!');
    }
    
    public function delete($id)
    {
        MedicalHistoryTemplate::findOrFail($id)->delete();
        session()->flash('success', 'Medical history template deleted successfully!');
    }
    
    public function toggleStatus($id)
    {
        $template = MedicalHistoryTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
        session()->flash('success', 'Template status updated!');
    }
    
    // CHANGE FROM private TO public
    public function resetForm()
    {
        $this->reset(['name', 'field_type', 'is_active', 'templateId', 'isEditing', 'showForm']);
        $this->is_active = true;
        $this->field_type = 'yes_no';
    }
}