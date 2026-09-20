<?php

namespace App\Livewire\OrderLab;

use App\Models\Encounter;
use App\Models\LabOrder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewEncounterResults extends Component
{
    public $encounterId;
    public $encounter;
    public $labOrders;

    public function mount($encounterId)
    {
        $this->encounterId = $encounterId;
        $this->loadEncounterData();
    }

    public function loadEncounterData()
    {
        $this->encounter = Encounter::with([
            'patient',
            'labOrders' => function ($query) {
                $query->with([
                    'labTest',
                    'labResults' => function ($q) {
                        $q->orderBy('parameter');
                    },
                    'order',
                    'verifiedBy'
                ])->whereIn('lab_orders.status', ['reported', 'verified'])
                    ->orderBy('lab_orders.created_at', 'desc');
            }
        ])->findOrFail($this->encounterId);

        $this->labOrders = $this->encounter->labOrders;
    }
    public function verifyTest($labOrderId)
    {
        $labOrder = LabOrder::findOrFail($labOrderId);

        if ($labOrder->status != 'reported') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Only reported tests can be verified.'
            ]);
            return;
        }

        $labOrder->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $this->loadEncounterData();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Test verified successfully!'
        ]);
    }



    public function verifyAll()
    {
        $reportedOrders = $this->labOrders->where('status', 'reported');

        if ($reportedOrders->isEmpty()) {
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'No tests require verification.'
            ]);
            return;
        }

        foreach ($reportedOrders as $labOrder) {
            $labOrder->update([
                'status' => 'verified',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
        }

        $this->loadEncounterData();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'All tests verified successfully!'
        ]);
    }
    public function orderMedication()
    {
        $this->dispatch('open-modal', 'order-medication');
    }


    public function render()
    {
        return view('livewire.order-lab.view-encounter-results');
    }
}
