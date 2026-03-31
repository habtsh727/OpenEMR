<?php

namespace App\Livewire\Cashier;

use App\Models\CuppingTherapy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CuppingPaymentQueue extends Component
{
    public $pendingPayments = [];
    public $completedPayments = [];
    public $selectedOrder = null;
    public $showPaymentModal = false;
    public $showReceiptModal = false;
    
    // Payment form fields
    public $orderId;
    public $payment_method = 'cash';
    public $payment_reference = '';
    public $amount_paid = 0;
    public $payment_notes = '';
    public $order_total = 0;
    
    // Search
    public $search = '';
    
    // Statistics
    public $stats = [
        'total_pending' => 0,
        'total_today' => 0,
        'total_revenue_today' => 0,
    ];
    
    protected $rules = [
        'payment_method' => 'required|in:cash,card,insurance,other',
        'payment_reference' => 'nullable|string|max:255',
        'amount_paid' => 'required|numeric|min:0',
        'payment_notes' => 'nullable|string',
    ];
    
    public function mount()
    {
        $this->loadData();
    }
    
    public function loadData()
    {
        // Load pending payments
        $pendingQuery = CuppingTherapy::with(['encounter.patient', 'items.cuppingType', 'items.cuppingLocation'])
            ->where('status', 'order_placed');
        
        if ($this->search) {
            $pendingQuery->where(function($q) {
                $q->whereHas('encounter.patient', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('id', 'like', '%' . $this->search . '%');
            });
        }
        
        $this->pendingPayments = $pendingQuery
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
        
        // Load today's completed payments
        try {
            $completedQuery = CuppingTherapy::with(['encounter.patient', 'paidBy'])
                ->where('status', 'payment_done')
                ->whereDate('paid_at', today());
            
            if ($this->search) {
                $completedQuery->where(function($q) {
                    $q->whereHas('encounter.patient', function($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('id', 'like', '%' . $this->search . '%');
                });
            }
            
            $this->completedPayments = $completedQuery
                ->orderBy('paid_at', 'desc')
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            // If paid_at column doesn't exist yet, use updated_at as fallback
            $completedQuery = CuppingTherapy::with(['encounter.patient', 'paidBy'])
                ->where('status', 'payment_done')
                ->whereDate('updated_at', today());
            
            if ($this->search) {
                $completedQuery->where(function($q) {
                    $q->whereHas('encounter.patient', function($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('id', 'like', '%' . $this->search . '%');
                });
            }
            
            $this->completedPayments = $completedQuery
                ->orderBy('updated_at', 'desc')
                ->get()
                ->toArray();
        }
        
        // Calculate statistics
        $this->stats['total_pending'] = count($this->pendingPayments);
        $this->stats['total_today'] = count($this->completedPayments);
        $this->stats['total_revenue_today'] = collect($this->completedPayments)->sum('final_amount');
    }
    
    public function updatedSearch()
    {
        $this->loadData();
    }
    
    public function openPaymentModal($orderId)
    {
        $order = CuppingTherapy::with(['encounter.patient', 'items.cuppingType', 'items.cuppingLocation'])
            ->findOrFail($orderId);
        
        $this->selectedOrder = $order->toArray();
        $this->orderId = $order->id;
        $this->order_total = $order->final_amount;
        $this->amount_paid = $order->final_amount;
        $this->payment_method = 'cash';
        $this->payment_reference = '';
        $this->payment_notes = '';
        $this->showPaymentModal = true;
    }
    
    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->selectedOrder = null;
        $this->reset(['orderId', 'payment_method', 'payment_reference', 'amount_paid', 'payment_notes', 'order_total']);
    }
    
    public function openReceiptModal($orderId)
    {
        $order = CuppingTherapy::with(['encounter.patient', 'items.cuppingType', 'items.cuppingLocation', 'paidBy'])
            ->findOrFail($orderId);
        
        $this->selectedOrder = $order->toArray();
        $this->showReceiptModal = true;
    }
    
    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
        $this->selectedOrder = null;
    }
    
    public function processPayment()
    {
        $this->validate();
        
        if ($this->amount_paid < $this->order_total) {
            $this->addError('amount_paid', 'Amount paid must be at least ' . number_format($this->order_total, 2) . ' ETB');
            return;
        }
        
        try {
            DB::beginTransaction();
            
            $order = CuppingTherapy::findOrFail($this->orderId);
            
            // Update order with payment details
            $updateData = [
                'status' => 'payment_done',
                'payment_method' => $this->payment_method,
                'payment_reference' => $this->payment_reference,
                'amount_paid' => $this->amount_paid,
                'payment_notes' => $this->payment_notes,
                'paid_at' => now(),
                'paid_by' => Auth::id(),
            ];
            
            $order->update($updateData);
            
            DB::commit();
            
            session()->flash('success', 'Payment processed successfully! Order has been sent to cupping department.');
            $this->closePaymentModal();
            $this->loadData();
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error processing payment: ' . $e->getMessage());
        }
    }
    
    public function cancelOrder($orderId)
    {
        try {
            DB::beginTransaction();
            
            $order = CuppingTherapy::findOrFail($orderId);
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => Auth::id(),
                'cancellation_reason' => 'Cancelled by cashier',
            ]);
            
            DB::commit();
            
            session()->flash('success', 'Order cancelled successfully.');
            $this->loadData();
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error cancelling order: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        return view('livewire.cashier.cupping-payment-queue');
    }
}