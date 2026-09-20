<?php

namespace App\Livewire;

use Livewire\Component;
use Flux\Flux;
use Livewire\Attributes\On;

class DetailServiceCategory extends Component
{

    public $name;
    public $code;   
    public $description;
    public $is_active;
    public $created_at;
    public $updated_at;
    public $services = [];

    #[On('detail-service-category')]
    public function viewDetails($id)
    {
        $category = \App\Models\ServiceCategory::find($id);
        if ($category) {
            $this->name = $category->name;
            $this->code = $category->code;
            $this->description = $category->description;
            $this->is_active = $category->is_active;
            $this->created_at = $category->created_at;
            $this->updated_at = $category->updated_at;
            $this->services = \App\Models\Service::where('category_id', $category->id)->get();
            Flux::modal('detail-service-category')->show();
        } else {
            session()->flash('error', 'Service Category not found.');
        }
    }

    public function render()
    {
        return view('livewire.detail-service-category');
    }
}
