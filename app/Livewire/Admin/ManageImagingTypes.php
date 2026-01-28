<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ImagingType;

class ManageImagingTypes extends Component
{
  
    public $types = [];
    public $name = '';
    public $fee = '';
    public $description = '';
    public $is_active = true;
    public $editingId = null;

    public function mount()
    {
        $this->loadTypes();
    }

    public function loadTypes()
    {
        $this->types = ImagingType::all();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'fee' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        if ($this->editingId) {
            $type = ImagingType::find($this->editingId);
            $type->update($this->getData());
        } else {
            ImagingType::create($this->getData());
        }

        $this->resetForm();
        $this->loadTypes();
    }

    public function edit($id)
    {
        $type = ImagingType::find($id);
        $this->editingId = $type->id;
        $this->name = $type->name;
        $this->fee = $type->fee;
        $this->description = $type->description;
        $this->is_active = $type->is_active;
    }

    public function delete($id)
    {
        ImagingType::find($id)->delete();
        $this->loadTypes();
    }

    private function getData()
    {
        return [
            'name' => $this->name,
            'fee' => $this->fee,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
    }

    private function resetForm()
    {
        $this->reset(['name', 'fee', 'description', 'is_active', 'editingId']);
    }

    public function render()
    {
        return view('livewire.admin.manage-imaging-types');
    }
}
