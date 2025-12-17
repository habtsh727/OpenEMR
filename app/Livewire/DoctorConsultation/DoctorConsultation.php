<?php

namespace App\Livewire\DoctorConsultation;

use Livewire\Component;
use App\Models\DoctorQueue; // <-- correct model namespace
use App\Models\PharmacyItem;

class DoctorConsultation extends Component
{

    public DoctorQueue $queue;

    public function mount(DoctorQueue $queue)
    {
        $this->queue = $queue;
    }

    public function render()
    {
        $pharmacyitems = PharmacyItem::all();
        return view('livewire.doctor-consultation.doctor-consultation',compact('pharmacyitems'));
    }
}
