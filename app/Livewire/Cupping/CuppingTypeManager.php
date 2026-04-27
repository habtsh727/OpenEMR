<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CuppingType;

class CuppingTypeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Form properties
    public $showForm = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $status = true;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'name' => 'required|string|max:255|unique:cupping_types,name',
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
        $type = CuppingType::findOrFail($id);
        $this->editingId = $id;
        $this->name = $type->name;
        $this->description = $type->description;
        $this->status = $type->status;
        $this->showForm = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['name'] = 'required|string|max:255|unique:cupping_types,name,' . $this->editingId;
        }
        $this->validate($rules);

        try {
            CuppingType::updateOrCreate(
                ['id' => $this->editingId],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]
            );

            $this->showAlertMessage(
                $this->editingId ? 'Cupping type updated successfully!' : 'Cupping type created successfully!',
                'success'
            );
            $this->resetForm();
            $this->dispatch('type-saved');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error saving cupping type: ' . $e->getMessage(), 'error');
        }
    }

    public function delete($id)
    {
        try {
            $type = CuppingType::findOrFail($id);

            // Check if it's being used in any packages
            $usageCount = \App\Models\CuppingPackageTreatment::where('cupping_type_id', $id)->count();
            if ($usageCount > 0) {
                $this->showAlertMessage("Cannot delete: This type is used in {$usageCount} package(s).", 'error');
                return;
            }

            $type->delete();
            $this->showAlertMessage('Cupping type deleted successfully!', 'success');
            $this->dispatch('type-deleted');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error deleting cupping type: ' . $e->getMessage(), 'error');
        }
    }

    public function toggleStatus($id)
    {
        $type = CuppingType::findOrFail($id);
        $type->update(['status' => !$type->status]);
        $this->showAlertMessage(
            $type->status ? 'Cupping type activated!' : 'Cupping type deactivated!',
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
        $types = CuppingType::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.cupping.cupping-type-manager', [
            'types' => $types,
        ]);
    }
}
