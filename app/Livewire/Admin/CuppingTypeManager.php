<?php

namespace App\Livewire\Admin;

use App\Models\CuppingType;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class CuppingTypeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $typeId = null;
    public $name = '';
    public $description = '';
    public $status = true;
    
    // Modal control
    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;
    public $isEditing = false;
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = ['search'];

    protected $rules = [
        'name' => 'required|string|max:255|unique:cupping_types,name',
        'description' => 'nullable|string',
        'status' => 'boolean',
    ];

    protected function rulesForUpdate()
    {
        return [
            'name' => 'required|string|max:255|unique:cupping_types,name,' . $this->typeId,
            'description' => 'nullable|string',
            'status' => 'boolean',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
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
        $type = CuppingType::findOrFail($id);
        
        $this->typeId = $type->id;
        $this->name = $type->name;
        $this->description = $type->description;
        $this->status = $type->status;
        
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
        $this->description = '';
        $this->status = true;
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->validate($this->rulesForUpdate());
        } else {
            $this->validate($this->rules);
        }

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
            ];

            if ($this->isEditing) {
                $type = CuppingType::find($this->typeId);
                $type->update($data);
                $message = 'Cupping type updated successfully!';
            } else {
                CuppingType::create($data);
                $message = 'Cupping type created successfully!';
            }

            $this->closeModal();
            $this->showAlertMessage($message, 'success');

        } catch (\Exception $e) {
            Log::error('Failed to save cupping type: ' . $e->getMessage());
            $this->showAlertMessage('Error saving cupping type: ' . $e->getMessage(), 'error');
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
            $type = CuppingType::findOrFail($this->deleteId);
            
            // Check if it has related records
            if ($type->sessionItems()->count() > 0) {
                $this->showAlertMessage('Cannot delete this type because it has associated therapy sessions.', 'error');
                $this->showDeleteModal = false;
                return;
            }
            
            $type->delete();
            
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->showAlertMessage('Cupping type deleted successfully!', 'success');

        } catch (\Exception $e) {
            Log::error('Failed to delete cupping type: ' . $e->getMessage());
            $this->showAlertMessage('Error deleting cupping type: ' . $e->getMessage(), 'error');
        }
    }

    public function toggleStatus($id)
    {
        $type = CuppingType::findOrFail($id);
        $type->update(['status' => !$type->status]);
        
        $status = $type->status ? 'activated' : 'deactivated';
        $this->showAlertMessage("Cupping type {$status} successfully!", 'success');
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
        $types = CuppingType::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.cupping-type-manager', [
            'types' => $types
        ]);
    }
}