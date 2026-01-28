<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\ImagingOrder;

class ViewImagingResults extends Component
{
    public $encounter;
    public $orders = [];
    public $selectedResult = null;
    public $showModal = false;

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = $this->encounter->imagingOrders()
            ->where('status', 'completed')
            ->with(['imagingType', 'bodyPart', 'imagingResult.radiologist'])
            ->latest()
            ->get();
    }

    public function viewResult($orderId)
    {
        $order = ImagingOrder::with('imagingResult.radiologist')->find($orderId);
        if ($order && $order->imagingResult) {
            $this->selectedResult = $order->imagingResult;
            $this->showModal = true;
        }
    }

    public function render()
    {
        return view('livewire.doctor.view-imaging-results');
    }
}