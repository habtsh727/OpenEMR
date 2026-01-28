<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BodyPart;

class ManageBodyParts extends Component
{
    public $bodyParts = [];
    public $name = '';
    public $code = '';
    public $description = '';
    public $is_active = true;
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:255|unique:body_parts,name',
        'code' => 'nullable|string|max:50|unique:body_parts,code',
        'description' => 'nullable|string',
        'is_active' => 'boolean'
    ];

    protected $messages = [
        'name.required' => 'Body part name is required.',
        'name.unique' => 'This body part already exists.',
        'code.unique' => 'This code is already in use.',
    ];

    public function mount()
    {
        $this->loadBodyParts();
    }

    public function loadBodyParts()
    {
        $this->bodyParts = BodyPart::orderBy('name')->get();
    }

    public function updated($propertyName)
    {
        // Clear unique validation when editing
        if ($this->editingId) {
            if ($propertyName === 'name') {
                $this->rules['name'] = 'required|string|max:255|unique:body_parts,name,' . $this->editingId;
            }
            if ($propertyName === 'code') {
                $this->rules['code'] = 'nullable|string|max:50|unique:body_parts,code,' . $this->editingId;
            }
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'code' => $this->code ?: null,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $bodyPart = BodyPart::find($this->editingId);
            $bodyPart->update($data);
            session()->flash('message', 'Body part updated successfully!');
        } else {
            BodyPart::create($data);
            session()->flash('message', 'Body part created successfully!');
        }

        $this->resetForm();
        $this->loadBodyParts();
    }

    public function edit($id)
    {
        $bodyPart = BodyPart::find($id);
        $this->editingId = $bodyPart->id;
        $this->name = $bodyPart->name;
        $this->code = $bodyPart->code ?? '';
        $this->description = $bodyPart->description ?? '';
        $this->is_active = $bodyPart->is_active;

        // Update validation rules for editing
        $this->rules['name'] = 'required|string|max:255|unique:body_parts,name,' . $id;
        $this->rules['code'] = 'nullable|string|max:50|unique:body_parts,code,' . $id;
    }

    public function delete($id)
    {
        $bodyPart = BodyPart::find($id);
        
        // Check if body part is used in any orders
        if ($bodyPart->imagingOrders()->exists()) {
            session()->flash('error', 'Cannot delete body part. It is being used in imaging orders.');
            return;
        }

        $bodyPart->delete();
        session()->flash('message', 'Body part deleted successfully!');
        $this->loadBodyParts();
    }

    public function toggleStatus($id)
    {
        $bodyPart = BodyPart::find($id);
        $bodyPart->update(['is_active' => !$bodyPart->is_active]);
        $this->loadBodyParts();
        session()->flash('message', 'Status updated successfully!');
    }

    private function resetForm()
    {
        $this->reset(['name', 'code', 'description', 'is_active', 'editingId']);
        $this->resetValidation();
        // Reset rules to default
        $this->rules = [
            'name' => 'required|string|max:255|unique:body_parts,name',
            'code' => 'nullable|string|max:50|unique:body_parts,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.manage-body-parts');
    }
}