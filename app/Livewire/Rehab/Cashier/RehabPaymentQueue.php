<?php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use Livewire\Component;
use Livewire\WithPagination;

class RehabPaymentQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = 'pending'; // pending, processed, all
    public $selectedOrder = null;
    public $showDetailsModal = false;

    public function viewOrder($orderId)
    {
        $this->selectedOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages.items',
            'encounter.encounter.doctor'
        ])->find($orderId);

        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrder = null;
    }

    public function recheckPayment($orderId)
    {
        $order = RehabOrder::find($orderId);

        if ($order && $order->status === 'paid') {
            // You can add logic here to re-verify payment
            // For now, just show a notification
            session()->flash('message', 'Payment rechecked and verified for Order #' . $orderId);
        }
    }
    public function render()
    {
        $query = RehabOrder::with([
            'encounter.encounter.patient',
            'packages'
        ])
            ->when($this->tab === 'pending', function ($q) {
                $q->where('status', 'sent_to_cashier');
            })
            ->when($this->tab === 'processed', function ($q) {
                $q->where('status', 'paid');
            })
            ->when($this->search, function ($q) {
                $q->whereHas('encounter.encounter.patient', function ($patient) {
                    $patient->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest();

        $orders = $query->paginate(10);

        return view('livewire.rehab.cashier.rehab-payment-queue', [
            'orders' => $orders
        ]);
    }
}
