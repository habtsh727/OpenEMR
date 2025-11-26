<?php

namespace App\Livewire\CardFee;

use Livewire\Component;
use Flux\Flux;

class CreateCardFee extends Component
{

    public $date;
    public $amount;
    public $is_active;
    public $price;
    public function save()
    {
        $this->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        // Logic to create a new Card Fee record
        \App\Models\CardFee::create([
            'date' => $this->date,
            'amount' => $this->amount,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', 'Card Fee created successfully.');

        $this->reset();
        Flux::modals()->close();

        $this->redirectRoute('card-fee',navigate: true);
        
    }   
    public function render()
    {
        return view('livewire.card-fee.create-card-fee');
    }
}
