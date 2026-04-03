<?php

namespace App\Livewire\Admin;

use App\Models\CuppingLocation;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class CuppingLocationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    
    // Form properties
    public $locationId = null;
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
        'name' => 'required|string|max:255|unique:cupping_locations,name',
        'description' => 'nullable|string',
        'status' => 'boolean',
    ];

    protected function rulesForUpdate()
    {
        return [
            'name' => 'required|string|max:255|unique:cupping_locations,name,' . $this->locationId,
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
        $location = CuppingLocation::findOrFail($id);
        
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->description = $location->description;
        $this->status = $location->status;
        
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
        $this->locationId = null;
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
                $location = CuppingLocation::find($this->locationId);
                $location->update($data);
                $message = 'Cupping location updated successfully!';
            } else {
                CuppingLocation::create($data);
                $message = 'Cupping location created successfully!';
            }

            $this->closeModal();
            $this->showAlertMessage($message, 'success');

        } catch (\Exception $e) {
            Log::error('Failed to save cupping location: ' . $e->getMessage());
            $this->showAlertMessage('Error saving cupping location: ' . $e->getMessage(), 'error');
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
            $location = CuppingLocation::findOrFail($this->deleteId);
            
            // Check if it has related records
            if ($location->sessionItems()->count() > 0) {
                $this->showAlertMessage('Cannot delete this location because it has associated therapy sessions.', 'error');
                $this->showDeleteModal = false;
                return;
            }
            
            $location->delete();
            
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->showAlertMessage('Cupping location deleted successfully!', 'success');

        } catch (\Exception $e) {
            Log::error('Failed to delete cupping location: ' . $e->getMessage());
            $this->showAlertMessage('Error deleting cupping location: ' . $e->getMessage(), 'error');
        }
    }

    public function toggleStatus($id)
    {
        $location = CuppingLocation::findOrFail($id);
        $location->update(['status' => !$location->status]);
        
        $status = $location->status ? 'activated' : 'deactivated';
        $this->showAlertMessage("Cupping location {$status} successfully!", 'success');
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
        $locations = CuppingLocation::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.admin.cupping-location-manager', [
            'locations' => $locations
        ]);
    }
}