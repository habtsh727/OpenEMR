<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicationOrder;
use App\Models\MedicationPayment;

class CashierOrderQueueComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = 'ordered';
    public $selectedOrderId = null;
    public $showPaymentModal = false;
    
    // Payment properties
    public $paymentAmount = 0;
    public $paymentMethod = 'cash';
    public $paymentDiscount = 0;
    public $paymentNotes = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function selectOrder($orderId)
    {
        $this->selectedOrderId = $orderId;
        $order = MedicationOrder::find($orderId);
        
        if ($order) {
            $this->paymentAmount = $order->payable_amount;
            $this->showPaymentModal = true;
        }
    }
    
    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0',
            'paymentMethod' => 'required|in:cash,card,insurance',
            'paymentDiscount' => 'nullable|numeric|min:0'
        ]);
        
        $order = MedicationOrder::findOrFail($this->selectedOrderId);
        
        // Create payment record
        $payment = MedicationPayment::create([
            'medication_order_id' => $order->id,
            'cashier_id' => auth()->id(),
            'amount' => $this->paymentAmount,
            'discount' => $this->paymentDiscount,
            'payment_method' => $this->paymentMethod
        ]);
        
        // Update order status
        $order->update([
            'status' => 'paid',
            'payable_amount' => $this->paymentAmount - $this->paymentDiscount
        ]);
        
        // Reset and close modal
        $this->reset(['paymentAmount', 'paymentMethod', 'paymentDiscount', 'paymentNotes']);
        $this->showPaymentModal = false;
        $this->selectedOrderId = null;
        
        $this->dispatch('payment-processed', orderId: $order->id);
        session()->flash('success', 'Payment processed successfully!');
    }
    
    public function render()
    {
        $orders = MedicationOrder::with(['encounter.patient', 'doctor'])
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('encounter.patient', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $stats = [
            'total_ordered' => MedicationOrder::where('status', 'ordered')->count(),
            'total_paid' => MedicationOrder::where('status', 'paid')->count(),
            'total_amount' => MedicationOrder::where('status', 'ordered')->sum('payable_amount'),
        ];
        
        return view('livewire.doctor.cashier-order-queue-component', [
            'orders' => $orders,
            'stats' => $stats
        ]);
    }
}
