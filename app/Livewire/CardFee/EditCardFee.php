<?php

namespace App\Livewire\CardFee;

use Livewire\Component;
use Livewire\Attributes\On;
use Flux\Flux;

class EditCardFee extends Component
{
    public $cardFeeId;
    public $date;
    public $amount;
    public $is_active;

    #[On('edit-card-fee')]
    public function edit($id)
    {
        $cardFee = \App\Models\CardFee::find($id);
        if ($cardFee) {
            $this->cardFeeId = $cardFee->id;
            $this->date = $cardFee->date;
            $this->amount = $cardFee->amount;
            $this->is_active = $cardFee->is_active;
            Flux::modal('edit-card-fee')->show();
            
        } else {
            session()->flash('error', 'Card Fee not found.');
        }
    
    }

    public function save()
    {
        $this->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $cardFee = \App\Models\CardFee::find($this->cardFeeId);
        if ($cardFee) {
            $cardFee->update([
                'date' => $this->date,
                'amount' => $this->amount,
                'is_active' => $this->is_active,
            ]);

            session()->flash('success', 'Card Fee updated successfully.');
             Flux::modals()->close();
            $this->redirectRoute('card-fee',navigate: true);
        } else {
            session()->flash('error', 'Card Fee not found.');
        }
    }
    
    public function render()
    {
        return view('livewire.card-fee.edit-card-fee');
    }
}
