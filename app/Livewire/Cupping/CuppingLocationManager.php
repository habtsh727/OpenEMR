<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CuppingLocation;

class CuppingLocationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public $showForm = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $status = true;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'name' => 'required|string|max:255|unique:cupping_locations,name',
        'description' => 'nullable|string|max:1000',
        'status' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $location = CuppingLocation::findOrFail($id);
        $this->editingId = $id;
        $this->name = $location->name;
        $this->description = $location->description;
        $this->status = $location->status;
        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|string|max:255|unique:cupping_locations,name,' . $this->editingId;
        }
        $this->validate($rules);

        try {
            CuppingLocation::updateOrCreate(
                ['id' => $this->editingId],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]
            );

            $this->showAlertMessage(
                $this->editingId ? 'Cupping location updated successfully!' : 'Cupping location created successfully!',
                'success'
            );
            $this->resetForm();
            $this->dispatch('location-saved');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error saving cupping location: ' . $e->getMessage(), 'error');
        }
    }

    public function delete($id)
    {
        try {
            $location = CuppingLocation::findOrFail($id);

            $usageCount = \App\Models\CuppingPackageTreatment::where('cupping_location_id', $id)->count();
            if ($usageCount > 0) {
                $this->showAlertMessage("Cannot delete: This location is used in {$usageCount} package(s).", 'error');
                return;
            }

            $location->delete();
            $this->showAlertMessage('Cupping location deleted successfully!', 'success');
            $this->dispatch('location-deleted');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error deleting cupping location: ' . $e->getMessage(), 'error');
        }
    }

    public function toggleStatus($id)
    {
        $location = CuppingLocation::findOrFail($id);
        $location->update(['status' => !$location->status]);
        $this->showAlertMessage(
            $location->status ? 'Cupping location activated!' : 'Cupping location deactivated!',
            'success'
        );
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->status = true;
        $this->resetErrorBag();
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
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

        return view('livewire.cupping.cupping-location-manager', [
            'locations' => $locations,
        ]);
    }
}
