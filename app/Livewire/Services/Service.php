<?php

namespace App\Livewire\Services;

use Livewire\Component;

class Service extends Component
{
    public $selectedId;

    public function edit($id)
    {

        $this->dispatch('edit-service',$id);
    }
    public function render()
    {
        $services = \App\Models\Service::paginate(10);
        return view('livewire.services.service', compact('services'));
    }

    public function viewDetails($id)
    {
        $this->dispatch('service-detail',$id);
    }
}
