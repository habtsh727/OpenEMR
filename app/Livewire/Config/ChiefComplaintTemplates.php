<?php

namespace App\Livewire\Config;

use App\Models\ChiefComplaintTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class ChiefComplaintTemplates extends Component
{
    use WithPagination;
    
    public $name;
    public $is_active = true;
    public $templateId;
    public $isEditing = false;
    
    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'is_active' => 'boolean'
    ];
    
    public function render()
    {
        $templates = ChiefComplaintTemplate::latest()->paginate(10);
        return view('livewire.config.chief-complaint-templates', compact('templates'));
    }
    
    public function create()
    {
        $this->validate();
        
        ChiefComplaintTemplate::create([
            'name' => $this->name,
            'is_active' => $this->is_active
        ]);
        
        $this->resetForm();
        session()->flash('success', 'Chief complaint template created successfully!');
    }
    
    public function edit($id)
    {
        $template = ChiefComplaintTemplate::findOrFail($id);
        $this->templateId = $id;
        $this->name = $template->name;
        $this->is_active = $template->is_active;
        $this->isEditing = true;
    }
    
    public function update()
    {
        $this->validate();
        
        $template = ChiefComplaintTemplate::findOrFail($this->templateId);
        $template->update([
            'name' => $this->name,
            'is_active' => $this->is_active
        ]);
        
        $this->resetForm();
        session()->flash('success', 'Chief complaint template updated successfully!');
    }
    
    public function delete($id)
    {
        ChiefComplaintTemplate::findOrFail($id)->delete();
        session()->flash('success', 'Chief complaint template deleted successfully!');
    }
    
    public function toggleStatus($id)
    {
        $template = ChiefComplaintTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
        session()->flash('success', 'Template status updated!');
    }
    
    private function resetForm()
    {
        $this->reset(['name', 'is_active', 'templateId', 'isEditing']);
        $this->is_active = true;
    }
}