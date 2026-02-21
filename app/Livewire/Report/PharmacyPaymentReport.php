<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicationOrder;
use App\Models\MedicationPayment;
use App\Models\User;
use App\Models\PharmacyItem;
use App\Models\CustomMedication;
use Illuminate\Support\Facades\DB;

class PharmacyPaymentReport extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $orderType = '';
    public $paymentMethod = '';
    public $status = '';
    public $cashierId = '';
    public $medicationType = ''; // 'standard', 'compound', 'all'
    
    public $showFilters = false;
    public $totalAmount = 0;
    public $totalOrders = 0;
    public $totalPayments = 0;
    public $totalDiscount = 0;
    
    public $paymentMethodStats = [];
    public $medicationTypeStats = [];
    
    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'orderType' => ['except' => ''],
        'paymentMethod' => ['except' => ''],
        'status' => ['except' => ''],
        'cashierId' => ['except' => ''],
        'medicationType' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function updated($property)
    {
        $this->resetPage();
        $this->calculateTotals();
    }

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
        
        // Medication type statistics (standard vs compound)
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
        $orders = $this->getBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $this->calculateTotals();

        return view('livewire.report.pharmacy-payment-report', [
            'orders' => $orders,
            'cashiers' => $this->cashiers,
        ]);
    }
}