<?php

namespace App\Livewire\Pharmacy\Report;

use Livewire\Component;
use App\Models\PharmacyStockTransaction;
use App\Models\PharmacyItem;
use App\Models\PharmacyBatch;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class SalesReport extends Component
{
    use WithPagination;

    // Filter properties
    public $reportType = 'daily'; // daily, weekly, monthly, custom
    public $dateFrom;
    public $dateTo;
    public $medicineId = '';
    public $selectedPeriod = 'today'; // today, yesterday, this_week, last_week, this_month, last_month
    public $sortBy = 'total_revenue';
    public $sortDirection = 'desc';

    // Chart data
    public $chartData = [];
    public $topMedicines = [];

    // Summary data
    public $totalSales = 0;
    public $totalRevenue = 0;
    public $totalItemsSold = 0;
    public $averageTransactionValue = 0;
    public $uniqueMedicinesSold = 0;
    public $totalTransactions = 0;

    protected $queryString = [
        'reportType' => ['except' => 'daily'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'medicineId' => ['except' => ''],
        'selectedPeriod' => ['except' => 'today'],
    ];

    public function mount()
    {
        $this->setDateRange();
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

    public function updatedMedicineId()
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
        $this->resetPage();
    }

    public function loadReportData()
    {
        $this->calculateSummary();
        $this->loadChartData();
        $this->loadTopMedicines();
    }

    private function calculateSummary()
    {
        $query = PharmacyStockTransaction::query()
            ->where('transaction_type', 'dispense')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        if ($this->medicineId) {
            $query->where('pharmacy_item_id', $this->medicineId);
        }

        $this->totalSales = $query->sum('total_price') ?? 0;
        $this->totalRevenue = $query->sum('total_price') ?? 0;
        $this->totalItemsSold = $query->sum('quantity') ?? 0;
        $this->totalTransactions = $query->count();
        $this->averageTransactionValue = $this->totalTransactions > 0
            ? $this->totalSales / $this->totalTransactions
            : 0;
        $this->uniqueMedicinesSold = $query->distinct('pharmacy_item_id')->count('pharmacy_item_id');
    }

    private function loadChartData()
    {
        $query = PharmacyStockTransaction::query()
            ->where('transaction_type', 'dispense')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        if ($this->medicineId) {
            $query->where('pharmacy_item_id', $this->medicineId);
        }

        switch ($this->reportType) {
            case 'daily':
                $this->chartData = $query->select(
                        DB::raw('DATE(created_at) as date'),
                        DB::raw('SUM(total_price) as total'),
                        DB::raw('COUNT(*) as transactions'),
                        DB::raw('SUM(quantity) as items_sold')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->toArray();
                break;

            case 'weekly':
                $this->chartData = $query->select(
                        DB::raw('YEARWEEK(created_at) as week'),
                        DB::raw('MIN(DATE(created_at)) as start_date'),
                        DB::raw('MAX(DATE(created_at)) as end_date'),
                        DB::raw('SUM(total_price) as total'),
                        DB::raw('COUNT(*) as transactions'),
                        DB::raw('SUM(quantity) as items_sold')
                    )
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get()
                    ->map(function($item) {
                        return [
                            'date' => "Week of {$item->start_date}",
                            'total' => $item->total,
                            'transactions' => $item->transactions,
                            'items_sold' => $item->items_sold,
                        ];
                    })
                    ->toArray();
                break;

            case 'monthly':
            default:
                $this->chartData = $query->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                        DB::raw('SUM(total_price) as total'),
                        DB::raw('COUNT(*) as transactions'),
                        DB::raw('SUM(quantity) as items_sold')
                    )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function($item) {
                        $date = \Carbon\Carbon::createFromFormat('Y-m', $item->month);
                        return [
                            'date' => $date->format('F Y'),
                            'total' => $item->total,
                            'transactions' => $item->transactions,
                            'items_sold' => $item->items_sold,
                        ];
                    })
                    ->toArray();
                break;
        }
    }

    private function loadTopMedicines()
    {
        $query = PharmacyStockTransaction::query()
            ->where('transaction_type', 'dispense')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);

        if ($this->medicineId) {
            $query->where('pharmacy_item_id', $this->medicineId);
        }

        $this->topMedicines = $query->select(
                'pharmacy_item_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->with('pharmacyItem')
            ->groupBy('pharmacy_item_id')
            ->orderBy($this->sortBy === 'total_revenue' ? 'total_revenue' : $this->sortBy, $this->sortDirection)
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'medicine_name' => $item->pharmacyItem->name ?? 'N/A',
                    'generic_name' => $item->pharmacyItem->generic_name ?? '',
                    'strength' => $item->pharmacyItem->strength ?? '',
                    'total_quantity' => $item->total_quantity,
                    'total_revenue' => $item->total_revenue,
                    'transaction_count' => $item->transaction_count,
                    'avg_price_per_unit' => $item->total_quantity > 0
                        ? $item->total_revenue / $item->total_quantity
                        : 0,
                ];
            })
            ->toArray();
    }

    public function exportReport()
    {
        // You can implement Excel/PDF export here
        session()->flash('message', 'Export feature coming soon!');
    }

    public function render()
    {
        $this->loadReportData();

        $medicines = PharmacyItem::where('is_active', 1)
            ->orderBy('name')
            ->get();

        // Get detailed sales transactions for table
        $transactions = PharmacyStockTransaction::with(['pharmacyItem', 'batch', 'createdBy'])
            ->where('transaction_type', 'dispense')
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->medicineId, function($query) {
                $query->where('pharmacy_item_id', $this->medicineId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.pharmacy.report.sales-report', [
            'medicines' => $medicines,
            'transactions' => $transactions,
        ]);
    }
}
