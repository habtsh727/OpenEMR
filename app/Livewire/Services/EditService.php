<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Flux\Flux;
use Livewire\Attributes\On;

class EditService extends Component
{

    public $serviceId;
    public $name;
    public $code;
    public $description;
    public $is_active;
    public $price;
    public $category_id;
    public $categories = [];

     #[On('edit-service')]
    public function edit($id)
    {

        $service = \App\Models\Service::find($id);
        if ($service) {
            $this->serviceId = $service->id;
            $this->name = $service->name;
            $this->code = $service->code;
            $this->description = $service->description;
            $this->is_active = $service->is_active;
            $this->categories = \App\Models\ServiceCategory::all();
            $this->category_id = $service->category_id;
            $this->price = $service->price;
           Flux::modal('edit-service')->show();
        } else {
            session()->flash('error', 'Service not found.');
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:services,code,' . $this->serviceId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $service = \App\Models\Service::find($this->serviceId);
        if ($service) {
            $service->update([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'category_id' => $this->category_id,
                'price' => $this->price,
            ]);
            session()->flash('success', 'Service updated successfully.');
            Flux::modal('edit-service')->close();

            $this->redirectRoute('services',navigate: true);
        } else {
            session()->flash('error', 'Service not found.');
        }
    }

    public function render()
    {
        return view('livewire.services.edit-service');
    }
}
