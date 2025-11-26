<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Flux\Flux;
use Livewire\Attributes\On;

class ServiceDetail extends Component
{

    public $name;
    public $code;   
    public $description;
    public $is_active;
    public $price;
    public $created_at;
    public $updated_at;
    public $category_name;

    #[On('service-detail')]
    public function viewDetails($id)
    {
        $service = \App\Models\Service::find($id);
        if ($service) {
            $this->name = $service->name;
            $this->code = $service->code;
            $this->description = $service->description;
            $this->is_active = $service->is_active;
            $this->price = $service->price;
            $this->created_at = $service->created_at;
            $this->updated_at = $service->updated_at;
            $this->category_name = $service->category ? $service->category->name : 'N/A';
            Flux::modal('service-detail')->show();
        } else {
            session()->flash('error', 'Service not found.');
        }
    }
    public function render()
    {
        return view('livewire.services.service-detail');
    }
}
