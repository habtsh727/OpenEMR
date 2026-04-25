<?php

namespace App\Livewire\Rehab;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RehabEncounter;
use App\Models\RehabOrder;
use App\Models\RehabPaymentInstallment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RehabFinanceReport extends Component
{
    use WithPagination;

    // Filter properties
    public $dateFrom;
    public $dateTo;
    public $paymentStatus = 'all'; // all, paid, partial, pending, overdue
    public $paymentType = 'all'; // all, full, installment
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;

    // Summary totals
    public $totalOrders = 0;
    public $totalAmount = 0;
    public $totalPaid = 0;
    public $totalPending = 0;
    public $totalOverdue = 0;
    public $collectionRate = 0;

    // Statistics by status
    public $statsByStatus = [];
    public $statsByPaymentType = [];
    public $monthlyStats = [];
    public $installmentStats = [];

    // Export
    public $showFilters = false;

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'paymentStatus' => ['except' => 'all'],
        'paymentType' => ['except' => 'all'],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateSummary();
    }

    public function updated($property)
    {
        $this->resetPage();
        $this->calculateSummary();
    }

    public function calculateSummary()
    {
        $query = $this->getBaseQuery();

        // Get all orders for statistics
        $orders = $query->get();

        $this->totalOrders = $orders->count();
        $this->totalAmount = $orders->sum('total_amount');
        $this->totalPaid = $orders->sum('paid_amount');
        $this->totalPending = $this->totalAmount - $this->totalPaid;

        // Calculate overdue (orders with pending installments past due date)
        $overdueOrders = 0;
        $overdueAmount = 0;

        foreach ($orders as $order) {
            $overdueInstallments = $order->paymentInstallments
                ->where('status', 'pending')
                ->where('due_date', '<', now());

            if ($overdueInstallments->isNotEmpty()) {
                $overdueOrders++;
                $overdueAmount += $overdueInstallments->sum(function ($installment) {
                    return $installment->getRemainingAmount();
                });
            }
        }

        $this->totalOverdue = $overdueAmount;
        $this->collectionRate = $this->totalAmount > 0
            ? round(($this->totalPaid / $this->totalAmount) * 100, 2)
            : 0;

        // Statistics by payment status
        $this->statsByStatus = [
            'paid' => [
                'count' => $orders->where('payment_status', 'paid')->count(),
                'amount' => $orders->where('payment_status', 'paid')->sum('total_amount'),
                'paid' => $orders->where('payment_status', 'paid')->sum('paid_amount'),
            ],
            'partial' => [
                'count' => $orders->where('payment_status', 'partial')->count(),
                'amount' => $orders->where('payment_status', 'partial')->sum('total_amount'),
                'paid' => $orders->where('payment_status', 'partial')->sum('paid_amount'),
            ],
            'pending' => [
                'count' => $orders->where('payment_status', 'pending')->count(),
                'amount' => $orders->where('payment_status', 'pending')->sum('total_amount'),
                'paid' => $orders->where('payment_status', 'pending')->sum('paid_amount'),
            ],
            'overdue' => [
                'count' => $orders->filter(function ($order) {
                    return $order->paymentInstallments
                        ->where('status', 'pending')
                        ->where('due_date', '<', now())
                        ->isNotEmpty();
                })->count(),
                'amount' => $overdueAmount,
                'paid' => 0,
            ],
        ];

        // Statistics by payment type
        $this->statsByPaymentType = [
            'full' => [
                'count' => $orders->where('payment_type', 'full')->count(),
                'amount' => $orders->where('payment_type', 'full')->sum('total_amount'),
                'paid' => $orders->where('payment_type', 'full')->sum('paid_amount'),
            ],
            'installment' => [
                'count' => $orders->where('payment_type', 'installment')->count(),
                'amount' => $orders->where('payment_type', 'installment')->sum('total_amount'),
                'paid' => $orders->where('payment_type', 'installment')->sum('paid_amount'),
            ],
        ];

        // Monthly statistics
        $this->monthlyStats = $this->getMonthlyStats();

        // Installment statistics
        $this->installmentStats = $this->getInstallmentStats();
    }

    protected function getBaseQuery()
    {
        return RehabOrder::with([
            'encounter.encounter.patient',
            'encounter.encounter.doctor',
            'paymentInstallments',
            'packages',
            'bedSelections'
        ])
        ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
        ->when($this->paymentStatus !== 'all', function ($query) {
            if ($this->paymentStatus === 'overdue') {
                // Overdue handled separately
                return $query;
            }
            return $query->where('payment_status', $this->paymentStatus);
        })
        ->when($this->paymentType !== 'all', function ($query) {
            return $query->where('payment_type', $this->paymentType);
        })
        ->when($this->search, function ($query) {
            $searchTerm = '%' . $this->search . '%';
            return $query->whereHas('encounter.encounter.patient', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                  ->orWhere('last_name', 'like', $searchTerm)
                  ->orWhere('card_number', 'like', $searchTerm);
            })->orWhere('id', 'like', $searchTerm);
        });
    }

    protected function getMonthlyStats()
    {
        $startDate = Carbon::parse($this->dateFrom)->startOfMonth();
        $endDate = Carbon::parse($this->dateTo)->endOfMonth();

        $stats = [];

        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $orders = RehabOrder::whereBetween('created_at', [$monthStart, $monthEnd])->get();

            $stats[] = [
                'month' => $date->format('F Y'),
                'total_orders' => $orders->count(),
                'total_amount' => $orders->sum('total_amount'),
                'paid_amount' => $orders->sum('paid_amount'),
                'collection_rate' => $orders->sum('total_amount') > 0
                    ? round(($orders->sum('paid_amount') / $orders->sum('total_amount')) * 100, 2)
                    : 0,
            ];
        }

        return $stats;
    }

    protected function getInstallmentStats()
    {
        $installments = RehabPaymentInstallment::whereHas('rehabOrder', function ($query) {
            $query->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59']);
        })->get();

        return [
            'total_installments' => $installments->count(),
            'paid_installments' => $installments->where('status', 'paid')->count(),
            'pending_installments' => $installments->where('status', 'pending')->count(),
            'partial_installments' => $installments->where('status', 'partial')->count(),
            'overdue_installments' => $installments->where('status', 'pending')
                ->where('due_date', '<', now())
                ->count(),
            'total_installment_amount' => $installments->sum('amount'),
            'paid_installment_amount' => $installments->sum('paid_amount'),
            'pending_installment_amount' => $installments->where('status', 'pending')->sum(function ($item) {
                return $item->getRemainingAmount();
            }),
        ];
    }

    public function getOrdersProperty()
    {
        $query = $this->getBaseQuery();

        // Handle overdue filter separately
        if ($this->paymentStatus === 'overdue') {
            $allOrders = $query->get();
            $orderIds = $allOrders->filter(function ($order) {
                return $order->paymentInstallments
                    ->where('status', 'pending')
                    ->where('due_date', '<', now())
                    ->isNotEmpty();
            })->pluck('id')->toArray();

            $query = RehabOrder::whereIn('id', $orderIds);
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getPaymentProgress($order)
    {
        if ($order->total_amount <= 0) return 0;
        return round(($order->paid_amount / $order->total_amount) * 100, 1);
    }

    public function getInstallmentProgress($installment)
    {
        if ($installment->amount <= 0) return 0;
        return round(($installment->paid_amount / $installment->amount) * 100, 1);
    }

    public function resetFilters()
    {
        $this->reset(['paymentStatus', 'paymentType', 'search', 'sortBy', 'sortDirection']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateSummary();
        $this->resetPage();
    }

    public function exportToExcel()
    {
        session()->flash('message', 'Excel export will be implemented');
    }

    public function exportToPDF()
    {
        session()->flash('message', 'PDF export will be implemented');
    }

    public function render()
    {
        $orders = $this->orders;

        return view('livewire.rehab.rehab-finance-report', [
            'orders' => $orders,
        ]);
    }
}
