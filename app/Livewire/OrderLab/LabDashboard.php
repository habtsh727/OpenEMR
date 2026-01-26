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
    public $showCollectModal = false;
    public $showResultModal = false;

    protected $listeners = [
        'sampleCollected' => 'handleSampleCollected',
        'resultAdded' => 'handleResultAdded',
        'orderCompleted' => '$refresh',
        'closeModal' => 'closeAllModals',
    ];

    public function mount()
    {
        $this->authorize('view_lab_order');
    }

    public function openCollectSample($labOrderId)
    {
        $this->selectedLabOrder = $labOrderId;
        $this->showCollectModal = true;
        $this->showResultModal = false;
        
        // Debug
        \Log::info('Opening collect sample modal for ID: ' . $labOrderId);
    }

    public function openAddResult($labOrderId)
    {
        $this->selectedLabOrder = $labOrderId;
        $this->showResultModal = true;
        $this->showCollectModal = false;
        
        \Log::info('Opening add result modal for ID: ' . $labOrderId);
    }

    public function markCompleted($labOrderId)
    {
        $labOrder = LabOrder::findOrFail($labOrderId);

        if ($labOrder->status !== 'reported') {
            $labOrder->update([
                'status' => 'reported',
            ]);
        }

        $order = $labOrder->order;
        $incompleteOrders = $order->labOrders()->whereNotIn('status', ['reported', 'cancelled'])->count();

        if ($incompleteOrders === 0) {
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        $this->dispatch('orderCompleted');
        session()->flash('success', 'Lab order marked as completed.');
    }

    public function handleSampleCollected()
    {
        $this->closeAllModals();
        $this->dispatch('$refresh');
        session()->flash('success', 'Sample collection recorded successfully.');
    }

    public function handleResultAdded()
    {
        $this->closeAllModals();
        $this->dispatch('$refresh');
        session()->flash('success', 'Test result added successfully.');
    }

    public function closeAllModals()
    {
        $this->showCollectModal = false;
        $this->showResultModal = false;
        $this->selectedLabOrder = null;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterPriority']);
        $this->resetPage();
    }

    public function render()
    {
        $labOrders = LabOrder::with([
            'order.encounter.patient',
            'labTest',
            'labSamples',
            'labResults'
        ])
            ->where('payment_status', 'paid')
            ->whereNotIn('status', ['cancelled'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('labTest', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('order.encounter.patient', function ($q2) {
                            $q2->where('first_name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                                ->orWhere('medical_record_number', 'like', '%' . $this->search . '%');
                        });
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
            ->paginate(12);

        $totalOrders = $labOrders->total();
        $pendingCount = LabOrder::where('payment_status', 'paid')
            ->where('status', 'pending')
            ->count();
        $processingCount = LabOrder::where('payment_status', 'paid')
            ->where('status', 'processing')
            ->count();
        $reportedCount = LabOrder::where('payment_status', 'paid')
            ->where('status', 'reported')
            ->count();

        return view('livewire.order-lab.lab-dashboard', [
            'labOrders' => $labOrders,
            'totalOrders' => $totalOrders,
            'pendingCount' => $pendingCount,
            'processingCount' => $processingCount,
            'reportedCount' => $reportedCount,
        ]);
    }
}