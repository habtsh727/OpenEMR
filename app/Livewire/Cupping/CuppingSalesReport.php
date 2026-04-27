<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CuppingTherapy;
use App\Models\CuppingSession;
use App\Models\CuppingPayment;
use App\Models\CuppingTherapyPackage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CuppingSalesReport extends Component
{
    use WithPagination;

    // Filters
    public $dateFrom;
    public $dateTo;
    public $reportType = 'daily'; // daily, weekly, monthly
    public $paymentMethod = 'all';
    public $search = '';
    public $perPage = 10;

    // Summary totals
    public $totalRevenue = 0;
    public $totalSessions = 0;
    public $totalPatients = 0;
    public $averagePerSession = 0;
    public $collectionRate = 0;

    // Chart data
    public $chartData = [];

    // Top packages
    public $topPackages = [];

    // Payment method breakdown
    public $paymentMethodStats = [];

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'reportType' => ['except' => 'daily'],
        'paymentMethod' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateSummary();
        $this->loadChartData();
        $this->loadTopPackages();
        $this->loadPaymentMethodStats();
    }

    public function updated($property)
    {
        $this->resetPage();
        $this->calculateSummary();
        $this->loadChartData();
        $this->loadTopPackages();
        $this->loadPaymentMethodStats();
    }

    public function calculateSummary()
    {
        $query = $this->getBaseQuery();

        $this->totalRevenue = $query->sum('session_amount');
        $this->totalSessions = $query->count();
        $this->totalPatients = $query->distinct('cupping_therapy_id')->count('cupping_therapy_id');
        $this->averagePerSession = $this->totalSessions > 0 ? $this->totalRevenue / $this->totalSessions : 0;

        // Collection rate
        $totalPaid = $query->sum('paid_amount');
        $this->collectionRate = $this->totalRevenue > 0 ? round(($totalPaid / $this->totalRevenue) * 100, 2) : 0;
    }

    public function loadChartData()
    {
        $query = CuppingSession::with('cuppingTherapy')
            ->whereBetween('session_date', [$this->dateFrom, $this->dateTo]);

        if ($this->paymentMethod !== 'all') {
            $query->whereHas('payments', function ($q) {
                $q->where('payment_method', $this->paymentMethod);
            });
        }

        switch ($this->reportType) {
            case 'daily':
                $this->chartData = $query->select(
                        DB::raw('DATE(session_date) as date'),
                        DB::raw('SUM(session_amount) as revenue'),
                        DB::raw('COUNT(*) as sessions')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => Carbon::parse($item->date)->format('M d'),
                            'revenue' => $item->revenue,
                            'sessions' => $item->sessions,
                        ];
                    })
                    ->toArray();
                break;

            case 'weekly':
                $this->chartData = $query->select(
                        DB::raw('YEARWEEK(session_date) as week'),
                        DB::raw('MIN(DATE(session_date)) as start_date'),
                        DB::raw('MAX(DATE(session_date)) as end_date'),
                        DB::raw('SUM(session_amount) as revenue'),
                        DB::raw('COUNT(*) as sessions')
                    )
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'label' => 'Week of ' . Carbon::parse($item->start_date)->format('M d'),
                            'revenue' => $item->revenue,
                            'sessions' => $item->sessions,
                        ];
                    })
                    ->toArray();
                break;

            case 'monthly':
                $this->chartData = $query->select(
                        DB::raw('DATE_FORMAT(session_date, "%Y-%m") as month'),
                        DB::raw('SUM(session_amount) as revenue'),
                        DB::raw('COUNT(*) as sessions')
                    )
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        $date = Carbon::createFromFormat('Y-m', $item->month);
                        return [
                            'label' => $date->format('F Y'),
                            'revenue' => $item->revenue,
                            'sessions' => $item->sessions,
                        ];
                    })
                    ->toArray();
                break;
        }
    }

    public function loadTopPackages()
    {
        $this->topPackages = CuppingTherapyPackage::whereHas('session', function ($q) {
                $q->whereBetween('session_date', [$this->dateFrom, $this->dateTo]);
            })
            ->select(
                'package_name_snapshot',
                DB::raw('COUNT(*) as session_count'),
                DB::raw('SUM(package_price_snapshot) as total_revenue')
            )
            ->groupBy('package_name_snapshot')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function loadPaymentMethodStats()
    {
        $this->paymentMethodStats = CuppingPayment::whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('payment_method')
            ->get()
            ->toArray();
    }

    protected function getBaseQuery()
    {
        return CuppingSession::query()
            ->whereBetween('session_date', [$this->dateFrom, $this->dateTo])
            ->when($this->paymentMethod !== 'all', function ($query) {
                $query->whereHas('payments', function ($q) {
                    $q->where('payment_method', $this->paymentMethod);
                });
            })
            ->when($this->search, function ($query) {
                $query->whereHas('cuppingTherapy.encounter.patient', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('card_number', 'like', '%' . $this->search . '%');
                });
            });
    }

    public function resetFilters()
    {
        $this->reset(['paymentMethod', 'search', 'reportType']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateSummary();
        $this->loadChartData();
        $this->loadTopPackages();
        $this->loadPaymentMethodStats();
        $this->resetPage();
    }

    public function exportToExcel()
    {
        $this->showAlertMessage('Excel export will be implemented soon', 'info');
    }

    public function exportToPDF()
    {
        $this->showAlertMessage('PDF export will be implemented soon', 'info');
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        $sessions = $this->getBaseQuery()
            ->with(['cuppingTherapy.encounter.patient', 'therapyPackage', 'payments'])
            ->orderBy('session_date', 'desc')
            ->paginate($this->perPage);

        return view('livewire.cupping.cupping-sales-report', [
            'sessions' => $sessions,
        ]);
    }
}
