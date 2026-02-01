<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicationOrder;
use App\Models\MedicationPayment;

class OrderQueueComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = 'ordered';
    public $selectedOrderId = null;
    public $showPaymentModal = false;
    public $showReceiptModal = false;
    
    // Payment properties
    public $paymentAmount = 0;
    public $paymentMethod = 'cash';
    public $paymentDiscount = 0;
    public $paymentNotes = '';
    
    // Receipt properties
    public $receiptData = null;
    
    // Toast properties - simple approach
    public $toastMessage = '';
    public $toastType = '';
    
    protected $listeners = ['paymentProcessed' => '$refresh'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function selectOrder($orderId)
    {
        $this->selectedOrderId = $orderId;
        $order = MedicationOrder::with(['items', 'encounter.patient'])->find($orderId);
        
        if ($order) {
            $this->paymentAmount = $order->payable_amount;
            $this->showPaymentModal = true;
        }
    }
    
    public function viewReceipt($orderId)
    {
        $order = MedicationOrder::with([
            'items.drug',
            'items.customMedication',
            'encounter.patient',
            'encounter.doctor',
            'payment'
        ])->find($orderId);
        
        if ($order && $order->payment) {
            $this->receiptData = [
                'order' => $order,
                'payment' => $order->payment,
                'items' => $order->items,
                'patient' => $order->encounter->patient,
                'doctor' => $order->encounter->doctor
            ];
            $this->showReceiptModal = true;
        }
    }
    
public function processPayment()
{
    $this->validate([
        'paymentAmount' => 'required|numeric|min:0',
        'paymentMethod' => 'required|in:cash,card,insurance',
        'paymentDiscount' => 'nullable|numeric|min:0',
        'paymentNotes' => 'nullable|string|max:500'
    ]);
    
    // Get the order
    $order = MedicationOrder::with(['encounter.patient'])->find($this->selectedOrderId);
    
    if (!$order) {
        $this->toastMessage = 'Order not found!';
        $this->toastType = 'error';
        return;
    }
    
    // Validate discount doesn't exceed amount
    $maxDiscount = $this->paymentAmount;
    if ($this->paymentDiscount > $maxDiscount) {
        $this->toastMessage = 'Discount cannot exceed payment amount.';
        $this->toastType = 'error';
        return;
    }
    
    // Calculate final amount after discount
    $finalAmount = $this->paymentAmount - $this->paymentDiscount;
    
    try {
        // Create payment record
        $payment = MedicationPayment::create([
            'medication_order_id' => $order->id,
            'cashier_id' => auth()->id(),
            'amount' => $this->paymentAmount,
            'discount' => $this->paymentDiscount,
            'payment_method' => $this->paymentMethod,
            'notes' => $this->paymentNotes,
            'paid_at' => now()
        ]);
        
        // Update order status
        $order->update([
            'status' => 'paid',
            'payable_amount' => $finalAmount,
            'discount_amount' => $order->discount_amount + $this->paymentDiscount
        ]);
        
        // ✅ ADD THIS: Create dispensation record for pharmacy
        \App\Models\MedicationDispensation::create([
            'medication_order_id' => $order->id,
            'pharmacist_id' => null, // Will be set when pharmacist processes it
            'status' => 'pending',
            'notes' => 'Awaiting pharmacy processing'
        ]);
        
        // Set success message
        $this->toastMessage = "Payment Successful! ₦" . number_format($finalAmount, 2) . " received for Order #{$order->id}";
        $this->toastType = 'success';
        
        // Reset and close modal
        $this->resetPaymentModal();
        $this->showPaymentModal = false;
        $this->selectedOrderId = null;
        
        // Show receipt after payment
        $this->viewReceipt($order->id);
        
        // Dispatch event for page refresh
        $this->dispatch('payment-processed', 
            orderId: $order->id,
            patientName: $order->encounter->patient->name
        );
        
        // Clear toast after 5 seconds
        $this->dispatch('clear-toast');
        
    } catch (\Exception $e) {
        \Log::error('Payment processing error: ' . $e->getMessage());
        \Log::error('Error details:', [
            'order_id' => $order->id,
            'payment_amount' => $this->paymentAmount,
            'discount' => $this->paymentDiscount,
            'user_id' => auth()->id(),
            'error_trace' => $e->getTraceAsString()
        ]);
        
        // Show detailed error for debugging
        $this->toastMessage = 'Payment Failed: ' . $e->getMessage();
        $this->toastType = 'error';
        
        // Clear toast after 5 seconds
        $this->dispatch('clear-toast');
    }
}
    
    public function clearToast()
    {
        $this->toastMessage = '';
        $this->toastType = '';
    }
    
    public function applyAdditionalDiscount()
    {
        $this->validate([
            'paymentDiscount' => 'required|numeric|min:0',
        ]);
        
        // Calculate new amount after discount
        $newAmount = max(0, $this->paymentAmount - $this->paymentDiscount);
        
        // Update the display
        $this->paymentAmount = $newAmount;
    }
    
    public function resetPaymentModal()
    {
        $this->reset([
            'paymentAmount', 'paymentMethod', 'paymentDiscount', 
            'paymentNotes', 'selectedOrderId'
        ]);
        $this->paymentMethod = 'cash';
    }
    
    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
        $this->receiptData = null;
    }
    
    public function printReceipt()
    {
        $this->dispatch('print-receipt');
    }
    
    public function render()
    {
        $orders = MedicationOrder::with(['encounter.patient', 'encounter.doctor'])
            ->when($this->status === 'ordered', function ($query) {
                $query->where('status', 'ordered');
            })
            ->when($this->status === 'paid', function ($query) {
                $query->where('status', 'paid');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('encounter.patient', function ($patientQuery) {
                        $patientQuery->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('id', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $stats = [
            'total_ordered' => MedicationOrder::where('status', 'ordered')->count(),
            'total_paid' => MedicationOrder::where('status', 'paid')->count(),
            'total_pending_amount' => MedicationOrder::where('status', 'ordered')->sum('payable_amount'),
            'total_collected_amount' => MedicationOrder::where('status', 'paid')->sum('payable_amount'),
        ];
        
        return view('livewire.cashier.order-queue-component', [
            'orders' => $orders,
            'stats' => $stats
        ]);
    }
}