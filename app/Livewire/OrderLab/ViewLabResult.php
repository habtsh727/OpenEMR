<?php

namespace App\Livewire\OrderLab;

use App\Models\LabOrder;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ViewLabResult extends Component
{
    public LabOrder $labOrder;

    public function mount(LabOrder $labOrder)
    {
        $this->authorize('view_lab_result');
        $this->labOrder = $labOrder->load([
            'order.encounter.patient',
            'labTest',
            'labResults',
            'labSamples',
            // Don't load verifiedBy until column exists
        ]);
    }

    public function printResult()
    {
        $this->dispatch('print-lab-result', labOrderId: $this->labOrder->id);
    }

    public function render()
    {
        return view('livewire.order-lab.view-lab-result');
    }
}
