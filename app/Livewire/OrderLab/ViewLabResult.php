<?php

namespace App\Livewire\OrderLab;

use App\Models\LabOrder;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ViewLabResult extends Component
{
    public LabOrder $labOrder;
    public $printMode = false;

    public function mount(LabOrder $labOrder)
    {
        $this->authorize('view_lab_result');
        $this->labOrder = $labOrder->load([
            'order.encounter.patient',
            'labTest',
            'labResults',
            'labSamples',
        ]);
        
        // Load verifiedBy if column exists
        if (isset($this->labOrder->verified_by)) {
            $this->labOrder->load('verifiedBy');
        }
    }

    public function printResult()
    {
        $this->printMode = true;
        $this->dispatch('print-initiated');
        
        // Use JavaScript to trigger print
        $this->dispatchBrowserEvent('print-lab-result', [
            'labOrderId' => $this->labOrder->id
        ]);
    }

    public function render()
    {
        return view('livewire.order-lab.view-lab-result');
    }
}