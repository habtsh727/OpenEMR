<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\ImagingOrder;

class ViewImagingResults extends Component
{
    public Encounter $encounter;
    public $orders = [];
    public $selectedResult = null;
    public $showModal = false;
    public $search = '';
    public $selectedType = null;
    public $dateRange = 'all';

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = $this->encounter->imagingOrders()
            ->where('status', 'completed')
            ->with(['imagingType', 'bodyPart', 'imagingResult.radiologist']);

        // Apply search filter
        if ($this->search) {
            $query->whereHas('imagingType', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhereHas('bodyPart', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply type filter
        if ($this->selectedType) {
            $query->where('imaging_type_id', $this->selectedType);
        }

        // Apply date filter
        if ($this->dateRange !== 'all') {
            $days = match($this->dateRange) {
                'today' => 1,
                'week' => 7,
                'month' => 30,
                'year' => 365,
                default => 0,
            };
            
            if ($days > 0) {
                $query->whereHas('imagingResult', function ($q) use ($days) {
                    $q->where('reported_at', '>=', now()->subDays($days));
                });
            }
        }

        $this->orders = $query->latest()->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'selectedType', 'dateRange'])) {
            $this->loadOrders();
        }
    }

    public function viewResult($orderId)
    {
        $order = ImagingOrder::with(['imagingResult.radiologist', 'imagingType', 'bodyPart'])->find($orderId);
        if ($order && $order->imagingResult) {
            $this->selectedResult = $order->imagingResult;
            $this->selectedResult->order_details = $order;
            $this->showModal = true;
        }
    }

    public function getImagingTypesProperty()
    {
        return $this->encounter->imagingOrders()
            ->where('status', 'completed')
            ->with('imagingType')
            ->get()
            ->pluck('imagingType')
            ->unique('id')
            ->sortBy('name');
    }

    public function getSummaryStatsProperty()
    {
        return [
            'total' => $this->orders->count(),
            'today' => $this->orders->filter(fn($order) => 
                $order->imagingResult && $order->imagingResult->reported_at->isToday()
            )->count(),
            'last_week' => $this->orders->filter(fn($order) => 
                $order->imagingResult && $order->imagingResult->reported_at->gte(now()->subWeek())
            )->count(),
        ];
    }

    public function downloadReport($resultId)
    {
        // This would trigger a download in a real application
        $this->dispatch('notify', 
            type: 'info', 
            message: 'Report download will be available soon.'
        );
    }

    public function printReport($resultId)
    {
        $this->dispatch('print-report', resultId: $resultId);
    }

    public function render()
    {
        return view('livewire.doctor.view-imaging-results', [
            'imagingTypes' => $this->imagingTypes,
            'summaryStats' => $this->summaryStats,
            'patient' => $this->encounter->patient,
        ]);
    }
}