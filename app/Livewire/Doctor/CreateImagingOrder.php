<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\ImagingOrder;
use App\Models\ImagingType;
use App\Models\BodyPart;

class CreateImagingOrder extends Component
{
    public $encounter;
    public $imagingTypes = [];
    public $bodyParts = [];
    public $selectedImagingType = null;
    public $selectedBodyPart = null;
    public $priority = 'routine';
    public $clinicalNotes = '';
    public $orders = [];
    public $fee = 0;

    protected $listeners = ['orderCreated' => 'loadOrders'];

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->loadData();
        $this->loadOrders();
    }

    public function loadData()
    {
        $this->imagingTypes = ImagingType::where('is_active', true)->get();
        $this->bodyParts = BodyPart::where('is_active', true)->get();
    }

    public function loadOrders()
    {
        $this->orders = $this->encounter->imagingOrders()
            ->with(['imagingType', 'bodyPart', 'imagingResult'])
            ->latest()
            ->get();
    }

    public function getFeeProperty()
    {
        if ($this->selectedImagingType) {
            $type = ImagingType::find($this->selectedImagingType);
            return $type ? $type->fee : 0;
        }
        return 0;
    }

    public function createOrder()
    {
        $this->validate([
            'selectedImagingType' => 'required|exists:imaging_types,id',
            'selectedBodyPart' => 'required|exists:body_parts,id',
            'priority' => 'required|in:routine,urgent',
            'clinicalNotes' => 'nullable|string',
        ]);

        ImagingOrder::create([
            'encounter_id' => $this->encounter->id,
            'imaging_type_id' => $this->selectedImagingType,
            'body_part_id' => $this->selectedBodyPart,
            'ordered_by' => auth()->id(),
            'amount' => $this->fee,
            'priority' => $this->priority,
            'clinical_notes' => $this->clinicalNotes,
            'status' => 'pending',
        ]);

        $this->reset(['selectedImagingType', 'selectedBodyPart', 'priority', 'clinicalNotes']);
        $this->dispatch('notify', type: 'success', message: 'Imaging order created!');
        $this->loadOrders();
    }

    public function cancelOrder($orderId)
    {
        $order = ImagingOrder::find($orderId);
        if ($order && $order->status == 'pending') {
            $order->update(['status' => 'cancelled']);
            $this->loadOrders();
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Order cancelled!']);
        }
    }

    public function render()
    {
        return view('livewire.doctor.create-imaging-order');
    }
}
