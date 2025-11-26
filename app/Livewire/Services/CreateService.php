<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Flux\Flux;

class CreateService extends Component
{

    public $name;
    public $code;
    public $description;
    public $is_active = true;
    public $category_id;
    public $price;

    public function save()
    {


        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:services,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
        ]);

     
        \App\Models\Service::create([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'category_id' => $this->category_id,
            'price' => $this->price,
        ]);
        session()->flash('success', 'Service created successfully.');
        $this->reset();
        Flux::modals()->close();

        $this->redirectRoute('services',navigate: true);
    }
    public function render()
    {
        $categories = \App\Models\ServiceCategory::all();
        return view('livewire.services.create-service', compact('categories'));
    }
}
