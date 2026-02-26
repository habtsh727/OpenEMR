<?php

namespace App\Livewire\Rehab;

use App\Models\RehabTreatmentType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class RehabTreatmentTypeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $typeId = null;
    public $name = '';
    public $icon = '';
    public $description = '';
    public $fields = [];
    public $isActive = true;
    public $order = 0;
    
    // Field builder properties
    public $fieldTypes = [
        'text' => 'Text Input',
        'textarea' => 'Text Area',
        'number' => 'Number',
        'select' => 'Dropdown Select',
        'radio' => 'Radio Buttons',
        'checkbox' => 'Checkboxes',
        'date' => 'Date Picker',
        'time' => 'Time Picker',
        'blood_pressure' => 'Blood Pressure',
    ];
    
    // Modal control
    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;
    public $isEditing = false;
    
    // Field editor
    public $showFieldModal = false;
    public $editingFieldIndex = null;
    public $fieldName = '';
    public $fieldLabel = '';
    public $fieldType = 'text';
    public $fieldRequired = false;
    public $fieldPlaceholder = '';
    public $fieldOptions = [];
    public $fieldMin = '';
    public $fieldMax = '';
    public $fieldStep = '';
    public $fieldHelp = '';
    
    // Option editor for select/radio/checkbox
    public $showOptionModal = false;
    public $editingOptionIndex = null;
    public $optionValue = '';
    public $optionLabel = '';
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search'];

    protected $listeners = ['closeFieldModal', 'closeOptionModal'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $type = RehabTreatmentType::findOrFail($id);
        
        $this->typeId = $type->id;
        $this->name = $type->name;
        $this->icon = $type->icon ?? '';
        $this->description = $type->description ?? '';
        $this->fields = $type->fields ?? [];
        $this->isActive = $type->is_active;
        $this->order = $type->order;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->typeId = null;
        $this->name = '';
        $this->icon = '';
        $this->description = '';
        $this->fields = [];
        $this->isActive = true;
        $this->order = 0;
        $this->isEditing = false;
        $this->resetFieldForm();
    }

    public function resetFieldForm()
    {
        $this->editingFieldIndex = null;
        $this->fieldName = '';
        $this->fieldLabel = '';
        $this->fieldType = 'text';
        $this->fieldRequired = false;
        $this->fieldPlaceholder = '';
        $this->fieldOptions = [];
        $this->fieldMin = '';
        $this->fieldMax = '';
        $this->fieldStep = '';
        $this->fieldHelp = '';
        $this->showFieldModal = false;
    }

    public function openFieldEditor($index = null)
    {
        if ($index !== null) {
            // Edit existing field
            $field = $this->fields[$index];
            $this->editingFieldIndex = $index;
            $this->fieldName = $field['name'];
            $this->fieldLabel = $field['label'];
            $this->fieldType = $field['type'];
            $this->fieldRequired = $field['required'] ?? false;
            $this->fieldPlaceholder = $field['placeholder'] ?? '';
            $this->fieldOptions = $field['options'] ?? [];
            $this->fieldMin = $field['min'] ?? '';
            $this->fieldMax = $field['max'] ?? '';
            $this->fieldStep = $field['step'] ?? '';
            $this->fieldHelp = $field['help'] ?? '';
        } else {
            // New field
            $this->resetFieldForm();
            $this->editingFieldIndex = null;
        }
        $this->showFieldModal = true;
    }

    public function saveField()
    {
        $this->validate([
            'fieldName' => 'required|regex:/^[a-z0-9_]+$/',
            'fieldLabel' => 'required',
            'fieldType' => 'required',
        ], [
            'fieldName.regex' => 'Field name must contain only lowercase letters, numbers, and underscores',
        ]);

        $field = [
            'name' => $this->fieldName,
            'label' => $this->fieldLabel,
            'type' => $this->fieldType,
            'required' => $this->fieldRequired,
        ];

        // Add optional fields based on type
        if ($this->fieldPlaceholder) {
            $field['placeholder'] = $this->fieldPlaceholder;
        }

        if (in_array($this->fieldType, ['number'])) {
            if ($this->fieldMin !== '') $field['min'] = (int)$this->fieldMin;
            if ($this->fieldMax !== '') $field['max'] = (int)$this->fieldMax;
            if ($this->fieldStep !== '') $field['step'] = (float)$this->fieldStep;
        }

        if (in_array($this->fieldType, ['select', 'radio', 'checkbox'])) {
            $field['options'] = $this->fieldOptions;
        }

        if ($this->fieldHelp) {
            $field['help'] = $this->fieldHelp;
        }

        if ($this->editingFieldIndex !== null) {
            // Update existing field
            $this->fields[$this->editingFieldIndex] = $field;
        } else {
            // Add new field
            $this->fields[] = $field;
        }

        $this->showFieldModal = false;
        $this->resetFieldForm();
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function moveFieldUp($index)
    {
        if ($index > 0) {
            $temp = $this->fields[$index];
            $this->fields[$index] = $this->fields[$index - 1];
            $this->fields[$index - 1] = $temp;
        }
    }

    public function moveFieldDown($index)
    {
        if ($index < count($this->fields) - 1) {
            $temp = $this->fields[$index];
            $this->fields[$index] = $this->fields[$index + 1];
            $this->fields[$index + 1] = $temp;
        }
    }

    public function openOptionModal($index = null)
    {
        $this->editingOptionIndex = $index;
        if ($index !== null) {
            $option = $this->fieldOptions[$index];
            $this->optionValue = $option['value'];
            $this->optionLabel = $option['label'];
        } else {
            $this->optionValue = '';
            $this->optionLabel = '';
        }
        $this->showOptionModal = true;
    }

    public function saveOption()
    {
        $this->validate([
            'optionValue' => 'required',
            'optionLabel' => 'required',
        ]);

        $option = [
            'value' => $this->optionValue,
            'label' => $this->optionLabel,
        ];

        if ($this->editingOptionIndex !== null) {
            $this->fieldOptions[$this->editingOptionIndex] = $option;
        } else {
            $this->fieldOptions[] = $option;
        }

        $this->showOptionModal = false;
        $this->optionValue = '';
        $this->optionLabel = '';
        $this->editingOptionIndex = null;
    }

    public function removeOption($index)
    {
        unset($this->fieldOptions[$index]);
        $this->fieldOptions = array_values($this->fieldOptions);
    }

    public function closeOptionModal()
    {
        $this->showOptionModal = false;
        $this->optionValue = '';
        $this->optionLabel = '';
        $this->editingOptionIndex = null;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'icon' => $this->icon,
                'description' => $this->description,
                'fields' => $this->fields,
                'is_active' => $this->isActive,
                'order' => $this->order ?: 0,
            ];

            if ($this->isEditing) {
                $type = RehabTreatmentType::find($this->typeId);
                $type->update($data);
                $message = 'Treatment type updated successfully!';
            } else {
                RehabTreatmentType::create($data);
                $message = 'Treatment type created successfully!';
            }

            $this->closeModal();
            $this->showAlertMessage($message, 'success');

        } catch (\Exception $e) {
            Log::error('Failed to save treatment type: ' . $e->getMessage());
            $this->showAlertMessage('Error saving treatment type: ' . $e->getMessage(), 'error');
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $type = RehabTreatmentType::findOrFail($this->deleteId);
            $type->delete();
            
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->showAlertMessage('Treatment type deleted successfully!', 'success');

        } catch (\Exception $e) {
            Log::error('Failed to delete treatment type: ' . $e->getMessage());
            $this->showAlertMessage('Error deleting treatment type: ' . $e->getMessage(), 'error');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        $types = RehabTreatmentType::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('order')
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.rehab.rehab-treatment-type-manager', [
            'types' => $types
        ]);
    }
}