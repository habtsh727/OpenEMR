<?php

namespace App\Livewire\Nursing;

use Livewire\Component;
use App\Models\Patient;

class Nursing extends Component
{


        public $search = '';

    public function render()
{
    $patients = Patient::query()
        // Search filter
        ->when($this->search, function ($q) {
            $search = "%{$this->search}%";
            $q->where(function ($query) use ($search) {
                $query->where('first_name', 'like', $search)
                    ->orWhere('middle_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhere('mother_name', 'like', $search)
                    ->orWhere('card_number', 'like', $search)
                    ->orWhere('phone_number1', 'like', $search)
                    ->orWhere('phone_number2', 'like', $search);
            });
        })
        // Only patients who have paid
        ->whereHas('cardPayments', function ($q) {
            $q->where('is_paid', true);
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('livewire.nursing.nursing', compact('patients'));
}
    
}
