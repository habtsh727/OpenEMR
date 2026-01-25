<?php

namespace App\Livewire\OrderLab;


use App\Models\LabOrder;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class LabDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPriority = '';
    public $selectedLabOrder = null;

    protected $listeners = [
        'sampleCollected' => '$refresh',
        'resultAdded' => '$refresh',
        'orderCompleted' => '$refresh',
    ];

    public function mount()
    {
        Gate::authorize('collect sample');
    }

    public function openCollectSample($labOrderId)
    {
        $this->selectedLabOrder = $labOrderId;
        $this->dispatch('open-modal', 'collect-sample');
    }

    public function openAddResult($labOrderId)
    {
        $this->selectedLabOrder = $labOrderId;
        $this->dispatch('open-modal', 'add-result');
    }

    public function markCompleted($labOrderId)
    {
        $labOrder = LabOrder::findOrFail($labOrderId);
        
        $labOrder->update([
            'status' => 'reported',
        ]);

        // Also update the parent order status if all lab orders are completed
        $order = $labOrder->order;
        $allCompleted = $order->labOrders()->where('status', '!=', 'reported')->count() === 0;
        
        if ($allCompleted) {
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        $this->dispatch('orderCompleted');
        session()->flash('message', 'Lab order marked as completed.');
    }

    public function render()
    {
        $labOrders = LabOrder::with(['order.encounter.patient', 'labTest', 'labSamples'])
            ->where('payment_status', 'paid')
            ->when($this->search, function ($query) {
                $query->whereHas('labTest', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterPriority, function ($query) {
                $query->where('priority', $this->filterPriority);
            })
            ->orderByRaw("FIELD(priority, 'stat', 'urgent', 'routine')")
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('livewire.order-lab.lab-dashboard', [
            'labOrders' => $labOrders,
        ]);
    }
}
