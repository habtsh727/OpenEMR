<?php

namespace App\Livewire\Cashier;

use App\Models\MedicationOrder;
use Livewire\Component;
use Livewire\WithPagination;

class MedicationOrders extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedOrder = null;

    public function selectOrder($orderId)
    {
        $this->selectedOrder = MedicationOrder::with([
            'encounter.patient',
            'encounter.doctor',
            'items' => function($query) {
                $query->with(['pharmacyItem', 'pharmacyBatch']);
            }
        ])->findOrFail($orderId);
        
        $this->dispatch('open-modal', 'view-order');
    }

    public function processPayment()
    {
        $order = MedicationOrder::findOrFail($this->selectedOrder->id);
        
        // Mark as paid and send to pharmacy
        $order->update([
            'status' => 'sent_to_pharmacy',
            'paid_amount' => $order->total_amount, // Mark as fully paid
            'cashier_id' => auth()->id(),
            'paid_at' => now(),
        ]);

        // Reset and refresh
        $this->reset(['selectedOrder']);
        $this->dispatch('close-modal', 'view-order');
        $this->dispatch('refresh');

        // Success message
        session()->flash('success', 'Payment processed and order sent to pharmacy!');
    }

    public function render()
    {
        $orders = MedicationOrder::with([
            'encounter.patient',
            'encounter.doctor',
            'items'
        ])
        ->where('status', 'sent_to_cashier') // Only show orders sent for payment
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->whereHas('encounter.patient', function ($q2) {
                    $q2->where('first_name', 'like', '%' . $this->search . '%')
                       ->orWhere('last_name', 'like', '%' . $this->search . '%')
                       ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                })
                ->orWhere('id', 'like', '%' . $this->search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // Stats
        $totalPending = MedicationOrder::where('status', 'sent_to_cashier')->count();
        $totalAmount = MedicationOrder::where('status', 'sent_to_cashier')->sum('total_amount');

        return view('livewire.cashier.medication-orders', [
            'orders' => $orders,
            'totalPending' => $totalPending,
            'totalAmount' => $totalAmount,
        ]);
    }
}