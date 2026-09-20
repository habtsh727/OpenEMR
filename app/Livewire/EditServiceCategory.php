<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use Flux\Flux;

class EditServiceCategory extends Component
{
    public $categoryId;
    public $name;
    public $code;
    public $description;
    public $is_active;

  #[On('edit-service-category')]

    public function edit($id)
    {
        $category = \App\Models\ServiceCategory::find($id);
        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->code = $category->code;
            $this->description = $category->description;
            $this->is_active = $category->is_active;
            Flux::modal('edit-service-category')->show();
        } else {
            session()->flash('error', 'Service Category not found.');
        }
    }
public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:service_categories,code,' . $this->categoryId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = \App\Models\ServiceCategory::find($this->categoryId);
        if ($category) {
            $category->update([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]);
            session()->flash('success', 'Service Category updated successfully.');
            Flux::modals()->close();

            $this->redirectRoute('service-category',navigate: true);
        } else {
            session()->flash('error', 'Service Category not found.');
        }
    }       
    

    public function render()
    {
        return view('livewire.edit-service-category');
    }
}
