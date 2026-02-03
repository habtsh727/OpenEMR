<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\ImagingOrder;

class ImagingPayments extends Component
{
    public $pendingOrders = [];
    public $search = '';
    public $selectedPriority = '';
    public $dateRange = 'all';
    
    protected $listeners = ['paymentProcessed' => 'loadOrders'];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = ImagingOrder::pending()
            ->with(['encounter.patient', 'imagingType', 'bodyPart']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('encounter.patient', function ($q) {
                        $q->where('first_name', 'like', "%{$this->search}%")
                          ->orWhere('last_name', 'like', "%{$this->search}%")
                          ->orWhere('phone', 'like', "%{$this->search}%");
                    })
                    ->orWhere('id', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedPriority) {
            $query->where('priority', $this->selectedPriority);
        }

        if ($this->dateRange != 'all') {
            $days = match($this->dateRange) {
                'today' => 1,
                'week' => 7,
                'month' => 30,
                'year' => 365,
                default => 0,
            };
            
            if ($days > 0) {
                $query->where('created_at', '>=', now()->subDays($days));
            }
        }

        $this->pendingOrders = $query->latest()->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'selectedPriority', 'dateRange'])) {
            $this->loadOrders();
        }
    }

    public function markAsPaid($orderId)
    {
        $order = ImagingOrder::find($orderId);
        if ($order) {
            $order->update(['status' => 'paid']);
            
            // Dispatch event for payment logging
            $this->dispatch('payment-logged', [
                'order_id' => $order->id,
                'amount' => $order->amount,
                'type' => 'imaging'
            ]);
            
            $this->dispatch('notify', [
                'type' => 'success', 
                'message' => 'Payment marked as completed successfully!'
            ]);
            
            $this->dispatch('paymentProcessed');
        }
    }

    public function showPaymentModal($orderId)
    {
        $this->dispatch('show-payment-modal', orderId: $orderId);
    }

    public function getSummaryStatsProperty()
    {
        $orders = $this->pendingOrders;
        
        return [
            'total' => $orders->count(),
            'total_amount' => $orders->sum('amount'),
            'urgent' => $orders->where('priority', 'urgent')->count(),
            'urgent_amount' => $orders->where('priority', 'urgent')->sum('amount'),
        ];
    }

    public function getPriorityOptionsProperty()
    {
        return [
            '' => 'All Priorities',
            'routine' => 'Routine',
            'urgent' => 'Urgent',
            'stat' => 'STAT',
        ];
    }

    public function render()
    {
        return view('livewire.cashier.imaging-payments', [
            'summaryStats' => $this->summaryStats,
            'priorityOptions' => $this->priorityOptions,
        ]);
    }
}