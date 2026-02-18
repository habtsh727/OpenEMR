<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ImagingOrder;
use App\Models\ImagingType;
use App\Models\BodyPart;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImagingPaymentReport extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $imagingTypeId = '';
    public $bodyPartId = '';
    public $priority = '';
    public $status = '';
    
    public $showFilters = false;
    public $totalAmount = 0;
    public $totalOrders = 0;
    public $typeStats = [];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updated($property)
    {
        $this->resetPage();
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $query = $this->getBaseQuery();
        
        $this->totalAmount = (clone $query)->sum('amount') ?? 0;
        $this->totalOrders = (clone $query)->count();
        
        // Get stats by imaging type
        $this->typeStats = ImagingOrder::select(
                'imaging_types.name as type_name',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->join('imaging_types', 'imaging_orders.imaging_type_id', '=', 'imaging_types.id')
            ->whereBetween('imaging_orders.order_date', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->imagingTypeId, fn($q) => $q->where('imaging_orders.imaging_type_id', $this->imagingTypeId))
            ->when($this->priority, fn($q) => $q->where('imaging_orders.priority', $this->priority))
            ->when($this->status, fn($q) => $q->where('imaging_orders.status', $this->status))
            ->groupBy('imaging_types.id', 'imaging_types.name')
            ->get();
    }

    protected function getBaseQuery()
    {
        return ImagingOrder::with(['imagingType', 'bodyPart', 'orderedBy', 'encounter.patient'])
            ->whereBetween('order_date', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->imagingTypeId, fn($q) => $q->where('imaging_type_id', $this->imagingTypeId))
            ->when($this->bodyPartId, fn($q) => $q->where('body_part_id', $this->bodyPartId))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->when($this->status, fn($q) => $q->where('status', $this->status));
    }

    public function getImagingTypesProperty()
    {
        return ImagingType::all();
    }

    public function getBodyPartsProperty()
    {
        return BodyPart::all();
    }

    public function resetFilters()
    {
        $this->reset(['imagingTypeId', 'bodyPartId', 'priority', 'status']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function render()
    {
        $orders = $this->getBaseQuery()
            ->orderBy('order_date', 'desc')
            ->paginate(15);
            
        $this->calculateTotals();

        return view('livewire.report.imaging-payment-report', [
            'orders' => $orders,
            'imagingTypes' => $this->imagingTypes,
            'bodyParts' => $this->bodyParts,
        ]);
    }
}
