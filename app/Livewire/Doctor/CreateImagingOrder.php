<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\ImagingOrder;
use App\Models\ImagingType;
use App\Models\BodyPart;

class CreateImagingOrder extends Component
{
    public Encounter $encounter;
    public $imagingTypes = [];
    public $bodyParts = [];
    public $selectedImagingType = null;
    public $selectedBodyPart = null;
    public $priority = 'routine';
    public $clinicalNotes = '';
    public $orders = [];
    public $fee = 0;
    public $showInstructions = false;

    protected $listeners = ['orderCreated' => 'loadOrders'];

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->loadData();
        $this->loadOrders();
    }

    public function loadData()
    {
        $this->imagingTypes = ImagingType::where('is_active', true)->orderBy('name')->get();
        $this->bodyParts = BodyPart::where('is_active', true)->orderBy('name')->get();
    }

    public function loadOrders()
    {
        $this->orders = $this->encounter->imagingOrders()
            ->with(['imagingType', 'bodyPart', 'imagingResult.radiologist'])
            ->latest()
            ->get();
    }

    public function updatedSelectedImagingType($value)
    {
        if ($value) {
            $type = ImagingType::find($value);
            $this->fee = $type ? $type->fee : 0;
        } else {
            $this->fee = 0;
        }
    }

    public function createOrder()
    {
        $this->validate([
            'selectedImagingType' => 'required|exists:imaging_types,id',
            'selectedBodyPart' => 'required|exists:body_parts,id',
            'priority' => 'required|in:routine,urgent',
            'clinicalNotes' => 'nullable|string|max:1000',
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

        $this->reset(['selectedImagingType', 'selectedBodyPart', 'priority', 'clinicalNotes', 'fee']);
        $this->dispatch('notify', 
            type: 'success', 
            message: 'Imaging order created successfully!'
        );
        $this->loadOrders();
    }

    public function cancelOrder($orderId)
    {
        $order = ImagingOrder::find($orderId);
        if ($order && $order->status == 'pending') {
            $order->update(['status' => 'cancelled']);
            $this->loadOrders();
            $this->dispatch('notify', 
                type: 'success', 
                message: 'Order cancelled successfully!'
            );
        }
    }

    public function viewResult($orderId)
    {
        $this->dispatch('open-result-modal', orderId: $orderId);
    }
     public function skipImaging()
    {
        return $this->redirect(route('doctor.order-medication', $this->encounter), navigate: true);
    }
     public function backToLabOrder()
    {
        return $this->redirect(route('consultation.assessment', $this->encounter), navigate: true);
    }

    public function render()
    {
        $pendingCount = $this->orders->where('status', 'pending')->count();
        $completedCount = $this->orders->where('status', 'completed')->count();
        
        return view('livewire.doctor.create-imaging-order', [
            'pendingCount' => $pendingCount,
            'completedCount' => $completedCount,
            'totalCount' => $this->orders->count(),
            'patient' => $this->encounter->patient,
        ]);
    }
}