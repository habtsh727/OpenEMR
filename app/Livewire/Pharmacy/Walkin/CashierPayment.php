<?php

namespace App\Livewire\Pharmacy\Walkin;

use Livewire\Component;
use App\Models\WalkinOrder;
use App\Models\WalkinPayment;
use Illuminate\Support\Facades\DB;

class CashierPayment extends Component
{
    public $orderNumber = '';
    public $order = null;
    public $paymentMethod = 'cash';
    public $amountPaid = 0;
    public $changeDue = 0;
    public $transactionId = '';

    public $searchOrder = '';
    public $pendingOrders = [];

    public function mount()
    {
        $this->loadPendingOrders();
    }

    public function loadPendingOrders()
    {
        $this->pendingOrders = WalkinOrder::with(['items']) // Load items relationship
            ->where('status', 'pending_payment')
            ->orderBy('created_at', 'desc')
            ->when($this->searchOrder, function($query) {
                $query->where('order_number', 'like', '%' . $this->searchOrder . '%')
                      ->orWhere('customer_name', 'like', '%' . $this->searchOrder . '%')
                      ->orWhere('customer_phone', 'like', '%' . $this->searchOrder . '%');
            })
            ->get();
    }

    public function updatedSearchOrder()
    {
        $this->loadPendingOrders();
    }

    public function selectOrder($orderId)
    {
        $this->order = WalkinOrder::with(['items.medicine'])->find($orderId);
        $this->orderNumber = $this->order->order_number;
        $this->amountPaid = $this->order->total_amount;
        $this->calculateChange();
    }

    public function calculateChange()
    {
        if ($this->order && $this->amountPaid >= $this->order->total_amount) {
            $this->changeDue = $this->amountPaid - $this->order->total_amount;
        } else {
            $this->changeDue = 0;
        }
    }

    public function updatedAmountPaid()
    {
        $this->calculateChange();
    }

    public function processPayment()
    {
        if (!$this->order) {
            session()->flash('error', 'No order selected');
            return;
        }

        if ($this->amountPaid < $this->order->total_amount) {
            session()->flash('error', 'Insufficient payment amount');
            return;
        }

        DB::beginTransaction();

        try {
            // Create payment record
            WalkinPayment::create([
                'order_id' => $this->order->id,
                'amount' => $this->order->total_amount,
                'payment_method' => $this->paymentMethod,
                'transaction_id' => $this->transactionId ?: null,
                'amount_paid' => $this->amountPaid,
                'change_due' => $this->changeDue,
                'collected_by' => auth()->id(),
            ]);

            // Update order status
            $this->order->update([
                'status' => 'paid',
                'paid_by' => auth()->id(),
                'paid_at' => now(),
            ]);

            DB::commit();

            session()->flash('success', "Payment collected successfully! Change: ETB " . number_format($this->changeDue, 2));

            // Reset form
            $this->reset(['order', 'orderNumber', 'amountPaid', 'changeDue', 'transactionId']);
            $this->loadPendingOrders();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pharmacy.walkin.cashier-payment');
    }
}
