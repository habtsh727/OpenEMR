<?php

namespace App\Livewire\Payments;

use Livewire\Component;
use App\Models\Patient;

class Payments extends Component
{

    public $search = '';


    public function render()
    {
       $patients = Patient::query()
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $search = "%{$this->search}%";

                    $query->where('first_name', 'like', $search)
                        ->orWhere('middle_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('mother_name', 'like', $search)
                        ->orWhere('card_number', 'like', $search)
                        ->orWhere('phone_number1', 'like', $search)
                        ->orWhere('phone_number2', 'like', $search)
                        ->orWhere('region', 'like', $search)
                        ->orWhere('region_zone', 'like', $search)
                        ->orWhere('region_woreda', 'like', $search)
                        ->orWhereRaw("CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ?", [$search])
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search]);
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('livewire.payments.payments',compact('patients'));
    }
}
