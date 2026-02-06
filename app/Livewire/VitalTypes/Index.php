<?php
// app/Livewire/VitalTypes/Index.php
namespace App\Livewire\VitalTypes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VitalType;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    public $showModal = false;
    public $modalTitle = 'Add Vital Type';
    public $editMode = false;
    public $confirmingDelete = false;
    
    // Form fields
    public $vitalTypeId;
    public $name = '';
    public $slug = '';
    public $data_type = 'number';
    public $options = [];
    public $optionInput = '';
    public $unit = '';
    public $sort_order = 0;
    public $is_active = true;
    
    // Filters
    public $search = '';
    public $dataTypeFilter = '';
    public $statusFilter = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        // Auto-generate slug from name
        $this->listeners['name-updated'] = 'generateSlug';
    }

    // Open modal for create
    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalTitle = 'Add Vital Type';
        $this->editMode = false;
        $this->showModal = true;
    }

    // Open modal for edit
    public function openEditModal($id)
    {
        $vitalType = VitalType::findOrFail($id);
        
        $this->vitalTypeId = $vitalType->id;
        $this->name = $vitalType->name;
        $this->slug = $vitalType->slug;
        $this->data_type = $vitalType->data_type;
        $this->options = $vitalType->getOptionsArray();
        $this->unit = $vitalType->unit;
        $this->sort_order = $vitalType->sort_order;
        $this->is_active = $vitalType->is_active;
        
        $this->modalTitle = 'Edit Vital Type';
        $this->editMode = true;
        $this->showModal = true;
    }

    // Save/Update
    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:vital_types,slug,' . ($this->editMode ? $this->vitalTypeId : 'NULL'),
            'data_type' => 'required|in:number,text,boolean,select',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'unit' => 'nullable|string|max:50',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ];

        // Additional validation for select type
        if ($this->data_type === 'select') {
            $rules['options'] = 'required|array|min:1';
        }

        $this->validate($rules, [
            'options.required' => 'At least one option is required for select type',
            'options.min' => 'At least one option is required for select type',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'data_type' => $this->data_type,
            'options' => $this->data_type === 'select' ? $this->options : null,
            'unit' => $this->unit,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->editMode) {
                $vitalType = VitalType::findOrFail($this->vitalTypeId);
                $vitalType->update($data);
                $message = 'Vital type updated successfully!';
            } else {
                VitalType::create($data);
                $message = 'Vital type created successfully!';
            }

            $this->showModal = false;
            $this->resetForm();
            $this->dispatch('show-toast', type: 'success', message: $message);
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // Delete confirmation
    public function confirmDelete($id)
    {
        $this->vitalTypeId = $id;
        $this->confirmingDelete = true;
    }

    // Delete
    public function delete()
    {
        try {
            $vitalType = VitalType::findOrFail($this->vitalTypeId);
            
            // Check if vital type is in use
            if ($vitalType->encounterVitals()->exists()) {
                $this->dispatch('show-toast', type: 'error', message: 'Cannot delete vital type that is already in use. You can deactivate it instead.');
                $this->confirmingDelete = false;
                return;
            }
            
            $vitalType->delete();
            
            $this->confirmingDelete = false;
            $this->dispatch('show-toast', type: 'success', message: 'Vital type deleted successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // Toggle status
    public function toggleStatus($id)
    {
        try {
            $vitalType = VitalType::findOrFail($id);
            $vitalType->update(['is_active' => !$vitalType->is_active]);
            
            $status = $vitalType->is_active ? 'activated' : 'deactivated';
            $this->dispatch('show-toast', type: 'success', message: "Vital type {$status} successfully!");
            
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Error: ' . $e->getMessage());
        }
    }

    // Add option for select type
    public function addOption()
    {
        $this->validate(['optionInput' => 'required|string|max:255']);
        
        if (!in_array($this->optionInput, $this->options)) {
            $this->options[] = $this->optionInput;
            $this->optionInput = '';
        }
    }

    // Remove option
    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    // Auto-generate slug
    public function generateSlug()
    {
        if (!$this->editMode && $this->name && !$this->slug) {
            $this->slug = VitalType::generateSlug($this->name);
        }
    }

    // Reset form
    private function resetForm()
    {
        $this->reset([
            'vitalTypeId',
            'name',
            'slug',
            'data_type',
            'options',
            'optionInput',
            'unit',
            'sort_order',
            'is_active'
        ]);
        $this->data_type = 'number';
        $this->sort_order = 0;
        $this->is_active = true;
    }

    // Close modal
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // Close delete confirmation
    public function closeDeleteModal()
    {
        $this->confirmingDelete = false;
        $this->vitalTypeId = null;
    }

    // Get vital types with filters
    public function getVitalTypesProperty()
    {
        return VitalType::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('unit', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->dataTypeFilter, function ($query) {
                $query->where('data_type', $this->dataTypeFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);
    }

    // Get data type options
    public function getDataTypeOptionsProperty()
    {
        return [
            'number' => 'Number',
            'text' => 'Text',
            'boolean' => 'Yes/No',
            'select' => 'Select Options'
        ];
    }

    public function render()
    {
        return view('livewire.vital-types.index', [
            'vitalTypes' => $this->vitalTypes,
            'dataTypeOptions' => $this->dataTypeOptions
        ]);
    }
}