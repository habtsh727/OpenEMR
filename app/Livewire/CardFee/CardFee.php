<?php

namespace App\Livewire\CardFee;

use Livewire\Component;


class CardFee extends Component
{


   public function changeStatus($id)
    {
        //if is_active field is true change to false and vice versa
      \App\Models\CardFee::find($id)->update([
            'is_active' => !\App\Models\CardFee::find($id)->is_active,
        ]);
       $this->redirectRoute('card-fee',navigate: true);
    }

     public function edit($id)
    {
         $this->dispatch('edit-card-fee',$id);
       
    }

    public function render()
    {
        $cardFees = \App\Models\CardFee::paginate(20);
        return view('livewire.card-fee.card-fee', compact('cardFees'));
    }
}
