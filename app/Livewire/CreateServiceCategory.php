<?php

namespace App\Livewire;

use Livewire\Component;
use Flux\Flux;

class CreateServiceCategory extends Component
{

 public $name;
 public $code;
 public $description;
 public $is_active = true;



    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:service_categories,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
     
        \App\Models\ServiceCategory::create([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ]);
        session()->flash('success', 'Service Category created successfully.');
        $this->reset();
        Flux::modals()->close();

        $this->redirectRoute('service-category',navigate: true);
    }
  

    public function render()
    {
        return view('livewire.create-service-category');
    }
}
