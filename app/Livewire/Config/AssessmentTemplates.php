<?php

namespace App\Livewire\Config;

use App\Models\AssessmentTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class AssessmentTemplates extends Component
{
    use WithPagination;
    
    public $diagnosis;
    public $context = 'OPD';
    public $active = true;
    
    public $contextFilter = null;
    public $activeFilter = null;
    public $search = '';
    
    public $templateId;
    public $isEditing = false;
    public $showForm = false;
    
    // Context options for dropdown
    public $contextOptions = ['OPD', 'ER', 'IPD'];
    
    protected $rules = [
        'diagnosis' => 'required|string|min:2|max:255',
        'context' => 'nullable|in:OPD,ER,IPD',
        'active' => 'boolean'
    ];
    
    public function render()
    {
        $templates = AssessmentTemplate::query()
            ->when($this->activeFilter != null, function ($query) {
                $query->where('active', $this->activeFilter);
            })
            ->when($this->contextFilter, function ($query) {
                $query->where('context', $this->contextFilter);
            })
            ->when($this->search, function ($query) {
                $query->where('diagnosis', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
            
        // Get unique contexts for filter
        $contexts = AssessmentTemplate::select('context')->distinct()->pluck('context');
        
        return view('livewire.config.assessment-templates', compact('templates', 'contexts'));
    }
    
    public function create()
    {
        $this->validate();
        
        AssessmentTemplate::create([
            'diagnosis' => $this->diagnosis,
            'context' => $this->context,
            'active' => $this->active
        ]);
        
        $this->resetForm();
        session()->flash('success', 'Assessment template created successfully!');
    }
    
    public function edit($id)
    {
        $template = AssessmentTemplate::findOrFail($id);
        $this->templateId = $id;
        $this->diagnosis = $template->diagnosis;
        $this->context = $template->context ?? 'OPD';
        $this->active = $template->active;
        
        $this->isEditing = true;
        $this->showForm = true;
    }
    
    public function update()
    {
        $this->validate();
        
        $template = AssessmentTemplate::findOrFail($this->templateId);
        $template->update([
            'diagnosis' => $this->diagnosis,
            'context' => $this->context,
            'active' => $this->active
        ]);
        
        $this->resetForm();
        session()->flash('success', 'Assessment template updated successfully!');
    }
    
    public function delete($id)
    {
        AssessmentTemplate::findOrFail($id)->delete();
        session()->flash('success', 'Assessment template deleted successfully!');
    }
    
    public function toggleStatus($id)
    {
        $template = AssessmentTemplate::findOrFail($id);
        $template->update(['active' => !$template->active]);
        session()->flash('success', 'Template status updated!');
    }
    
    // Reset form method
    public function resetForm()
    {
        $this->reset(['diagnosis', 'context', 'active', 'templateId', 'isEditing', 'showForm']);
        $this->active = true;
        $this->context = 'OPD';
    }
}

