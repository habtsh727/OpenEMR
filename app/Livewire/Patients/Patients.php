<?php

namespace App\Livewire\Patients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;

class Patients extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->dispatch('edit-patients', $id);
    }

    public function viewDetails($id)
    {
        $this->dispatch('patient-detail', $id);
    }

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

        return view('livewire.patients.patients', compact('patients'));
    }
}
