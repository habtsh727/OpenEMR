<?php

namespace App\Livewire\Pharmacy\Walkin;

use Livewire\Component;
use App\Models\WalkinOrder;
use App\Models\WalkinOrderItem;
use App\Models\WalkinPayment;
use App\Models\PharmacyItem;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class WalkinSalesReport extends Component
{
    use WithPagination;

    // Filter properties
    public $reportType = 'daily'; // daily, weekly, monthly, custom
    public $dateFrom;
    public $dateTo;
    public $selectedPeriod = 'today';
    public $status = 'all'; // all, paid, dispensed, cancelled
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Chart data
    public $salesChartData = [];
    public $topProducts = [];

    // Summary statistics
    public $totalOrders = 0;
    public $totalRevenue = 0;
    public $totalItemsSold = 0;
    public $averageOrderValue = 0;
    public $uniqueCustomers = 0;
    public $pendingOrders = 0;
    public $dispensedOrders = 0;

    protected $queryString = [
        'reportType' => ['except' => 'daily'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'selectedPeriod' => ['except' => 'today'],
        'status' => ['except' => 'all'],
    ];

    public function mount()
    {
        $this->setDateRange();
        $this->loadReportData();
    }

    public function setDateRange()
    {
        switch ($this->selectedPeriod) {
            case 'today':
                $this->dateFrom = now()->startOfDay()->format('Y-m-d');
                $this->dateTo = now()->endOfDay()->format('Y-m-d');
                $this->reportType = 'daily';
                break;

            case 'yesterday':
                $this->dateFrom = now()->subDay()->startOfDay()->format('Y-m-d');
                $this->dateTo = now()->subDay()->endOfDay()->format('Y-m-d');
                $this->reportType = 'daily';
                break;

            case 'this_week':
                $this->dateFrom = now()->startOfWeek()->format('Y-m-d');
                $this->dateTo = now()->endOfWeek()->format('Y-m-d');
                $this->reportType = 'weekly';
                break;

            case 'last_week':
                $this->dateFrom = now()->subWeek()->startOfWeek()->format('Y-m-d');
                $this->dateTo = now()->subWeek()->endOfWeek()->format('Y-m-d');
                $this->reportType = 'weekly';
                break;

            case 'this_month':
                $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->endOfMonth()->format('Y-m-d');
                $this->reportType = 'monthly';
                break;

            case 'last_month':
                $this->dateFrom = now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->dateTo = now()->subMonth()->endOfMonth()->format('Y-m-d');
                $this->reportType = 'monthly';
                break;

            default:
                if (!$this->dateFrom || !$this->dateTo) {
                    $this->dateFrom = now()->startOfDay()->format('Y-m-d');
                    $this->dateTo = now()->endOfDay()->format('Y-m-d');
                }
                break;
        }
    }

    public function updatedSelectedPeriod()
    {
        $this->setDateRange();
        $this->resetPage();
        $this->loadReportData();
    }

    public function updatedDateFrom()
    {
        $this->selectedPeriod = 'custom';
        $this->reportType = 'custom';
        $this->resetPage();
        $this->loadReportData();
    }

    public function updatedDateTo()
    {
        $this->selectedPeriod = 'custom';
        $this->reportType = 'custom';
        $this->resetPage();
        $this->loadReportData();
    }

    public function updatedStatus()
    {
        $this->resetPage();
        $this->loadReportData();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function loadReportData()
    {
        $this->calculateSummary();
        $this->loadSalesChart();
        $this->loadTopProducts();
    }

    private function calculateSummary()
    {
        $query = WalkinOrder::query()
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $this->totalOrders = $query->count();
        $this->totalRevenue = $query->sum('total_amount');
        $this->averageOrderValue = $this->totalOrders > 0 ? $this->totalRevenue / $this->totalOrders : 0;

        // Total items sold
        $this->totalItemsSold = WalkinOrderItem::query()
            ->whereHas('order', function($q) {
                $q->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
                if ($this->status !== 'all') {
                    $q->where('status', $this->status);
                }
            })
            ->sum('quantity');

        // Unique customers (excluding "Walk-in Customer" default)
        $this->uniqueCustomers = WalkinOrder::query()
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('customer_name', '!=', 'Walk-in Customer')
            ->distinct('customer_name')
            ->count('customer_name');

        // Status counts
        $this->pendingOrders = WalkinOrder::where('status', 'pending_payment')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->count();

        $this->dispensedOrders = WalkinOrder::where('status', 'dispensed')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->count();
    }

    private function loadSalesChart()
    {
        $query = WalkinOrder::query()
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('status', 'dispensed');

        switch ($this->reportType) {
            case 'daily':
                $this->salesChartData = $query->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(total_amount) as total'),
                        DB::raw('COUNT(*) as orders'),
                        DB::raw('AVG(total_amount) as average')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function($item) {
                        return [
                            'label' => \Carbon\Carbon::parse($item->date)->format('M d'),
                            'revenue' => $item->total,
                            'orders' => $item->orders,
                            'average' => $item->average,
                        ];
                    })
                    ->toArray();
                break;

            case 'weekly':
                $this->salesChartData = $query->select(
                        DB::raw('YEARWEEK(created_at) as week'),
                        DB::raw('MIN(DATE(created_at)) as start_date'),
                        DB::raw('MAX(DATE(created_at)) as end_date'),
                        DB::raw('SUM(total_amount) as total'),
                        DB::raw('COUNT(*) as orders')
                    )
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get()
                    ->map(function($item) {
                        return [
                            'label' => "Week of " . \Carbon\Carbon::parse($item->start_date)->format('M d'),
                            'revenue' => $item->total,
                            'orders' => $item->orders,
                            'average' => $item->orders > 0 ? $item->total / $item->orders : 0,
                        ];
                    })
                    ->toArray();
                break;

            case 'monthly':
            default:
                $this->salesChartData = $query->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                        DB::raw('SUM(total_amount) as total'),
                        DB::raw('COUNT(*) as orders')
                    )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function($item) {
                        $date = \Carbon\Carbon::createFromFormat('Y-m', $item->month);
                        return [
                            'label' => $date->format('F Y'),
                            'revenue' => $item->total,
                            'orders' => $item->orders,
                            'average' => $item->orders > 0 ? $item->total / $item->orders : 0,
                        ];
                    })
                    ->toArray();
                break;
        }
    }

    private function loadTopProducts()
    {
        $this->topProducts = WalkinOrderItem::query()
            ->select(
                'medicine_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT order_id) as order_count')
            )
            ->with('medicine')
            ->whereHas('order', function($q) {
                $q->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                  ->where('status', 'dispensed');
            })
            ->groupBy('medicine_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->medicine->name ?? 'N/A',
                    'generic_name' => $item->medicine->generic_name ?? '',
                    'strength' => $item->medicine->strength ?? '',
                    'quantity' => $item->total_quantity,
                    'revenue' => $item->total_revenue,
                    'orders' => $item->order_count,
                    'avg_price' => $item->total_quantity > 0 ? $item->total_revenue / $item->total_quantity : 0,
                ];
            })
            ->toArray();
    }

    public function exportReport()
    {
        // You can implement Excel export here
        session()->flash('success', 'Export feature coming soon!');
    }

    public function render()
    {
        // Get detailed orders for table
        $orders = WalkinOrder::with(['items.medicine', 'cashier', 'dispenser'])
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->status !== 'all', function($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return view('livewire.pharmacy.walkin.walkin-sales-report', [
            'orders' => $orders,
        ]);
    }
}
