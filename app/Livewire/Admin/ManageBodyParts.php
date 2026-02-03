<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BodyPart;

class ManageBodyParts extends Component
{
    use WithPagination;
    
    public $name = '';
    public $code = '';
    public $description = '';
    public $is_active = true;
    public $editingId = null;
    public $search = '';
    public $activeFilter = null;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'name.required' => 'Body part name is required.',
        'name.unique' => 'This body part already exists.',
        'code.unique' => 'This code is already in use.',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        
        if ($this->editingId && in_array($propertyName, ['name', 'code'])) {
            $this->rules['name'] = 'required|string|max:255|unique:body_parts,name,' . $this->editingId;
            $this->rules['code'] = 'nullable|string|max:50|unique:body_parts,code,' . $this->editingId;
        }
        
        // Auto-generate code from name when editing and code is empty
        if ($propertyName === 'name' && !$this->editingId && empty($this->code)) {
            $this->code = strtoupper(preg_replace('/[^A-Z0-9]/', '_', $this->name));
        }
    }

    public function save()
    {
        $this->validate();

        $data = $this->getData();

        if ($this->editingId) {
            $bodyPart = BodyPart::find($this->editingId);
            $bodyPart->update($data);
            session()->flash('success', 'Body part updated successfully!');
        } else {
            BodyPart::create($data);
            session()->flash('success', 'Body part created successfully!');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        $bodyPart = BodyPart::findOrFail($id);
        $this->editingId = $bodyPart->id;
        $this->name = $bodyPart->name;
        $this->code = $bodyPart->code ?? '';
        $this->description = $bodyPart->description ?? '';
        $this->is_active = $bodyPart->is_active;
        $this->showForm = true;
        
        // Update validation rules
        $this->rules['name'] = 'required|string|max:255|unique:body_parts,name,' . $id;
        $this->rules['code'] = 'nullable|string|max:50|unique:body_parts,code,' . $id;
    }

    public function delete($id)
    {
        $bodyPart = BodyPart::findOrFail($id);
        
        // Check if body part is used
        if ($bodyPart->imagingOrders()->exists()) {
            session()->flash('error', 'Cannot delete body part. It is being used in ' . $bodyPart->imagingOrders()->count() . ' imaging order(s).');
            return;
        }

        $bodyPart->delete();
        session()->flash('success', 'Body part deleted successfully!');
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $bodyPart = BodyPart::findOrFail($id);
        
        $this->dispatch('swal:confirm', [
            'title' => 'Delete Body Part',
            'text' => "Are you sure you want to delete '{$bodyPart->name}'?",
            'icon' => 'warning',
            'confirmText' => 'Yes, delete it!',
            'cancelText' => 'Cancel',
            'method' => 'delete',
            'params' => [$id]
        ]);
    }

    public function toggleStatus($id)
    {
        $bodyPart = BodyPart::findOrFail($id);
        $bodyPart->update(['is_active' => !$bodyPart->is_active]);
        
        $status = $bodyPart->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Body part {$status} successfully!");
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getBodyPartsProperty()
    {
        return BodyPart::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->activeFilter != null, function ($query) {
                $query->where('is_active', $this->activeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    private function getData()
    {
        return [
            'name' => trim($this->name),
            'code' => $this->code ? trim($this->code) : null,
            'description' => trim($this->description),
            'is_active' => $this->is_active,
        ];
    }

    private function resetForm()
    {
        $this->reset(['name', 'code', 'description', 'is_active', 'editingId', 'showForm']);
        $this->resetValidation();
        // Reset rules to default
        $this->rules = [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ];
    }

    public function render()
    {
        $bodyParts = $this->bodyParts;
        $totalCount = BodyPart::count();
        $activeCount = BodyPart::where('is_active', true)->count();
        
        return view('livewire.admin.manage-body-parts', compact('bodyParts', 'totalCount', 'activeCount'));
    }
}