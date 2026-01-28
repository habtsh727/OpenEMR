<?php

namespace App\Livewire\Radiology;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ImagingOrder;
use App\Models\ImagingResult;

class RadiologyDashboard extends Component
{
    use WithFileUploads;

    public $paidOrders = [];
    public $selectedOrder = null;
    public $images = [];
    public $report = '';
    public $status = 'completed';

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->paidOrders = ImagingOrder::paid()
            ->with(['encounter.patient', 'imagingType', 'bodyPart'])
            ->whereDoesntHave('imagingResult', function($q) {
                $q->where('status', 'completed');
            })
            ->latest()
            ->get();
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = ImagingOrder::with(['encounter.patient', 'imagingType'])->find($orderId);
        $this->reset(['images', 'report']);
    }

    public function submitResult()
    {
        $this->validate([
            'report' => 'required|string|min:10',
            'images.*' => 'nullable|image|max:5120',
            'status' => 'required|in:in_progress,completed,rejected',
        ]);

        if (!$this->selectedOrder) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Select an order first']);
            return;
        }

        // Upload images
        $uploadedImages = [];
        foreach ($this->images as $image) {
            $path = $image->store('imaging-results', 'public');
            $uploadedImages[] = $path;
        }

        // Create result
        ImagingResult::create([
            'imaging_order_id' => $this->selectedOrder->id,
            'radiologist_id' => auth()->id(),
            'report' => $this->report,
            'images' => $uploadedImages,
            'status' => $this->status,
            'reported_at' => now(),
        ]);

        // Update order status
        if ($this->status == 'completed') {
            $this->selectedOrder->update(['status' => 'completed']);
        }

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Results submitted!']);
        $this->reset(['selectedOrder', 'images', 'report']);
        $this->loadOrders();
    }

    public function render()
    {
        return view('livewire.radiology.radiology-dashboard');
    }
}
