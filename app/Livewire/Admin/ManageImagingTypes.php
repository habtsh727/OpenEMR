<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ImagingType;

class ManageImagingTypes extends Component
{
    use WithPagination;
    
    public $name = '';
    public $fee = '';
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
        'fee' => 'required|numeric|min:0|max:999999.99',
        'description' => 'nullable|string|max:500',
        'is_active' => 'boolean'
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        $data = $this->getData();

        if ($this->editingId) {
            $type = ImagingType::find($this->editingId);
            $type->update($data);
            session()->flash('success', 'Imaging type updated successfully!');
        } else {
            ImagingType::create($data);
            session()->flash('success', 'Imaging type created successfully!');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        $type = ImagingType::findOrFail($id);
        $this->editingId = $type->id;
        $this->name = $type->name;
        $this->fee = $type->fee;
        $this->description = $type->description ?? '';
        $this->is_active = $type->is_active;
        $this->showForm = true;
    }

    public function delete($id)
    {
        $type = ImagingType::findOrFail($id);
        
        // Check if type is being used
        if ($type->imagingOrders()->exists()) {
            session()->flash('error', 'Cannot delete imaging type. It is being used in ' . $type->imagingOrders()->count() . ' order(s).');
            return;
        }

        $type->delete();
        session()->flash('success', 'Imaging type deleted successfully!');
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $type = ImagingType::findOrFail($id);
        $type->update(['is_active' => !$type->is_active]);
        
        session()->flash('success', 'Status updated successfully!');
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

    public function getTypesProperty()
    {
        return ImagingType::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
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
            'fee' => (float) $this->fee,
            'description' => trim($this->description),
            'is_active' => $this->is_active,
        ];
    }

    private function resetForm()
    {
        $this->reset(['name', 'fee', 'description', 'is_active', 'editingId', 'showForm']);
        $this->resetValidation();
    }

    public function render()
    {
        $types = $this->types;
        $totalCount = ImagingType::count();
        $activeCount = ImagingType::where('is_active', true)->count();
        
        return view('livewire.admin.manage-imaging-types', compact('types', 'totalCount', 'activeCount'));
    }
}