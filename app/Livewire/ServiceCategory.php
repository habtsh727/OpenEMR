<?php

namespace App\Livewire;

use Livewire\Component;

use Flux\Flux;

class ServiceCategory extends Component
{
    public $selectedId;
    


public function edit($id)
{
    $this->dispatch('edit-service-category',$id);
}

public function confirmDelete($id)
{
    $this->selectedId = $id;

    // open modal
    $this->dispatch('open-modal', name: 'delete-profile');
}

public function delete()
{
    \App\Models\ServiceCategory::find($this->selectedId)?->delete();

 
    session()->flash('success', 'Service Category deleted successfully.');

    $this->reset('selectedId');
       // close modal
     Flux::modals()->close();
}


   public function deleteCategory($id)
    {

        $category = \App\Models\ServiceCategory::find($id);
        if ($category) {
            $category->delete();
            session()->flash('success', 'Service Category deleted successfully.');
            $this->redirectRoute('service-category',navigate: true);
        } else {
            session()->flash('error', 'Service Category not found.');
        }
    }
    
    public function viewDetails($id)
    {
        $this->dispatch('detail-service-category',$id);
   
    }

    public function render()
    {
        $categories = \App\Models\ServiceCategory::paginate(10);
        return view('livewire.service-category', compact('categories'));
    }
}
