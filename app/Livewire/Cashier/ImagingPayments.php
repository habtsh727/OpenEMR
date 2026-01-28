<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\ImagingOrder;

class ImagingPayments extends Component
{
    public $pendingOrders = [];
    public $search = '';

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = ImagingOrder::pending()
            ->with(['encounter.patient', 'imagingType', 'bodyPart']);

        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                  ->orWhere('last_name', 'like', "%{$this->search}%");
            });
        }

        $this->pendingOrders = $query->latest()->get();
    }

    public function markAsPaid($orderId)
    {
        $order = ImagingOrder::find($orderId);
        if ($order) {
            $order->update(['status' => 'paid']);
            $this->loadOrders();
            $this->dispatch('notify', [
                'type' => 'success', 
                'message' => 'Order marked as paid!'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.cashier.imaging-payments');
    }
}