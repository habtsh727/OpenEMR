<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicationOrder;
use App\Models\MedicationPayment;
use App\Models\WalkinOrder;
use App\Models\WalkinPayment;
use App\Models\User;
use App\Models\PharmacyItem;
use App\Models\CustomMedication;
use Illuminate\Support\Facades\DB;

class PharmacyPaymentReport extends Component
{
    use WithPagination;

    // Report type selector
    public $reportTab = 'prescription'; // prescription, walkin

    // Prescription filters
    public $dateFrom;
    public $dateTo;
    public $orderType = '';
    public $paymentMethod = '';
    public $status = '';
    public $cashierId = '';
    public $medicationType = '';

    // Walk-in filters
    public $walkinDateFrom;
    public $walkinDateTo;
    public $walkinStatus = '';
    public $walkinPaymentMethod = '';
    public $walkinCashierId = '';
    public $walkinSearch = '';

    public $showFilters = false;

    // Prescription totals
    public $totalAmount = 0;
    public $totalOrders = 0;
    public $totalPayments = 0;
    public $totalDiscount = 0;
    public $paymentMethodStats = [];
    public $medicationTypeStats = [];

    // Walk-in totals
    public $walkinTotalOrders = 0;
    public $walkinTotalRevenue = 0;
    public $walkinTotalItems = 0;
    public $walkinAvgOrderValue = 0;
    public $walkinPaymentMethodStats = [];

    protected $queryString = [
        'reportTab' => ['except' => 'prescription'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'orderType' => ['except' => ''],
        'paymentMethod' => ['except' => ''],
        'status' => ['except' => ''],
        'cashierId' => ['except' => ''],
        'medicationType' => ['except' => ''],
        'walkinDateFrom' => ['except' => ''],
        'walkinDateTo' => ['except' => ''],
        'walkinStatus' => ['except' => ''],
        'walkinPaymentMethod' => ['except' => ''],
        'walkinCashierId' => ['except' => ''],
    ];

    public function mount()
    {
        // Prescription defaults
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');

        // Walk-in defaults
        $this->walkinDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->walkinDateTo = now()->format('Y-m-d');

        $this->calculateTotals();
        $this->calculateWalkinTotals();
    }

    public function updated($property)
    {
        if (str_starts_with($property, 'walkin')) {
            $this->resetPage('walkinPage');
            $this->calculateWalkinTotals();
        } else {
            $this->resetPage('prescriptionPage');
            $this->calculateTotals();
        }
    }

    public function updatedReportTab()
    {
        $this->resetPage();
    }

    // ==================== PRESCRIPTION METHODS ====================

    public function calculateTotals()
    {
        $query = $this->getBaseQuery();

        // Get paid orders only for financial totals
        $paidQuery = (clone $query)->where('status', 'paid');

        $this->totalAmount = (clone $paidQuery)->sum('total_amount') ?? 0;
        $this->totalDiscount = (clone $paidQuery)->sum('discount_amount') ?? 0;
        $this->totalOrders = $query->count();
        $this->totalPayments = MedicationPayment::whereIn('medication_order_id', $paidQuery->pluck('id'))
            ->sum('amount') ?? 0;

        // Payment method statistics
        $this->paymentMethodStats = MedicationPayment::select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->whereIn('medication_order_id', $paidQuery->pluck('id'))
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method')
            ->toArray();

        // Medication type statistics
        $this->medicationTypeStats = [
            'standard' => [
                'count' => $this->getStandardMedicationCount($query),
                'total' => $this->getStandardMedicationTotal($query)
            ],
            'compound' => [
                'count' => $this->getCompoundMedicationCount($query),
                'total' => $this->getCompoundMedicationTotal($query)
            ]
        ];
    }

    protected function getBaseQuery()
    {
        return MedicationOrder::with([
                'encounter.patient',
                'payments.cashier',
                'dispensation.pharmacist',
                'items.drug',
                'items.customMedication'
            ])
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->orderType, fn($q) => $q->where('order_type', $this->orderType))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->medicationType === 'standard', function($q) {
                $q->whereHas('items', function($itemQuery) {
                    $itemQuery->whereNotNull('drug_id')->whereNull('custom_medication_id');
                });
            })
            ->when($this->medicationType === 'compound', function($q) {
                $q->whereHas('items', function($itemQuery) {
                    $itemQuery->whereNotNull('custom_medication_id');
                });
            });
    }

    protected function getStandardMedicationCount($query)
    {
        return (clone $query)->whereHas('items', function($q) {
            $q->whereNotNull('drug_id')->whereNull('custom_medication_id');
        })->count();
    }

    protected function getStandardMedicationTotal($query)
    {
        return (clone $query)->whereHas('items', function($q) {
            $q->whereNotNull('drug_id')->whereNull('custom_medication_id');
        })->where('status', 'paid')->sum('payable_amount') ?? 0;
    }

    protected function getCompoundMedicationCount($query)
    {
        return (clone $query)->whereHas('items', function($q) {
            $q->whereNotNull('custom_medication_id');
        })->count();
    }

    protected function getCompoundMedicationTotal($query)
    {
        return (clone $query)->whereHas('items', function($q) {
            $q->whereNotNull('custom_medication_id');
        })->where('status', 'paid')->sum('payable_amount') ?? 0;
    }

    public function getCashiersProperty()
    {
        $cashierIds = MedicationPayment::whereNotNull('cashier_id')
            ->distinct()
            ->pluck('cashier_id')
            ->toArray();

        return User::whereIn('id', $cashierIds)->get();
    }

    // ==================== WALK-IN METHODS ====================

    public function calculateWalkinTotals()
    {
        $query = $this->getWalkinBaseQuery();
        $completedQuery = (clone $query)->where('status', 'dispensed');

        $this->walkinTotalOrders = $query->count();
        $this->walkinTotalRevenue = $completedQuery->sum('total_amount') ?? 0;
        $this->walkinTotalItems = $this->getWalkinTotalItems();
        $this->walkinAvgOrderValue = $this->walkinTotalOrders > 0 ? $this->walkinTotalRevenue / $this->walkinTotalOrders : 0;

        // Payment method statistics for walk-in
        $orderIds = $completedQuery->pluck('id')->toArray();

        $this->walkinPaymentMethodStats = WalkinPayment::select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->whereIn('order_id', $orderIds)
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method')
            ->toArray();
    }

    protected function getWalkinBaseQuery()
    {
        return WalkinOrder::with(['items.medicine', 'cashier', 'dispenser', 'payments'])
            ->whereBetween('created_at', [$this->walkinDateFrom . ' 00:00:00', $this->walkinDateTo . ' 23:59:59'])
            ->when($this->walkinStatus, fn($q) => $q->where('status', $this->walkinStatus))
            ->when($this->walkinPaymentMethod, function($q) {
                $q->whereHas('payments', function($pq) {
                    $pq->where('payment_method', $this->walkinPaymentMethod);
                });
            })
            ->when($this->walkinCashierId, function($q) {
                $q->where(function($query) {
                    $query->where('cashier_id', $this->walkinCashierId)
                          ->orWhere('dispensed_by', $this->walkinCashierId)
                          ->orWhere('created_by', $this->walkinCashierId);
                });
            })
            ->when($this->walkinSearch, function($q) {
                $q->where('order_number', 'like', '%' . $this->walkinSearch . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->walkinSearch . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->walkinSearch . '%');
            });
    }

    protected function getWalkinTotalItems()
    {
        $orderIds = $this->getWalkinBaseQuery()->pluck('id')->toArray();

        return \App\Models\WalkinOrderItem::whereIn('order_id', $orderIds)
            ->whereHas('order', function($q) {
                $q->where('status', 'dispensed');
            })
            ->sum('quantity') ?? 0;
    }

    public function getWalkinCashiersProperty()
    {
        $cashierIds = WalkinPayment::whereNotNull('collected_by')
            ->distinct()
            ->pluck('collected_by')
            ->toArray();

        $pharmacistIds = WalkinOrder::whereNotNull('dispensed_by')
            ->distinct()
            ->pluck('dispensed_by')
            ->toArray();

        $allIds = array_merge($cashierIds, $pharmacistIds);

        if (empty($allIds)) {
            return collect();
        }

        return User::whereIn('id', $allIds)->get();
    }

    public function resetWalkinFilters()
    {
        $this->reset(['walkinStatus', 'walkinPaymentMethod', 'walkinCashierId', 'walkinSearch']);
        $this->walkinDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->walkinDateTo = now()->format('Y-m-d');
        $this->calculateWalkinTotals();
    }

    // ==================== COMMON METHODS ====================

    public function resetFilters()
    {
        $this->reset(['orderType', 'paymentMethod', 'status', 'cashierId', 'medicationType']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
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
        // Prescription orders
        $prescriptionOrders = $this->getBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'prescriptionPage');

        // Walk-in orders
        $walkinOrders = $this->getWalkinBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'walkinPage');

        $this->calculateTotals();
        $this->calculateWalkinTotals();

        return view('livewire.report.pharmacy-payment-report', [
            'prescriptionOrders' => $prescriptionOrders,
            'walkinOrders' => $walkinOrders,
            'cashiers' => $this->cashiers,
            'walkinCashiers' => $this->walkinCashiers,
        ]);
    }
}
