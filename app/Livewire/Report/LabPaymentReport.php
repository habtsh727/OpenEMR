<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LabPaymentReport extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $labTestId = '';
    public $priority = '';
    public $status = '';
    public $processedBy = '';

    public $showFilters = false;
    public $totalAmount = 0;
    public $totalOrders = 0;
    public $testStats = [];

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'labTestId' => ['except' => ''],
        'priority' => ['except' => ''],
        'status' => ['except' => ''],
        'processedBy' => ['except' => ''],
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
        // Get total amount by joining with lab_tests
        $this->totalAmount = LabOrder::join('lab_tests', 'lab_orders.lab_test_id', '=', 'lab_tests.id')
            ->whereBetween('lab_orders.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->labTestId, fn($q) => $q->where('lab_orders.lab_test_id', $this->labTestId))
            ->when($this->priority, fn($q) => $q->where('lab_orders.priority', $this->priority))
            ->when($this->status, fn($q) => $q->where('lab_orders.payment_status', $this->status))
            ->when($this->processedBy, fn($q) => $q->where('lab_orders.paid_by', $this->processedBy))
            ->sum('lab_tests.price') ?? 0;

        $this->totalOrders = $this->getBaseQuery()->count();

        // Get stats by test
        $this->testStats = LabOrder::select(
            'lab_tests.name as test_name',
            'lab_tests.code as test_code',
            DB::raw('COUNT(*) as order_count'),
            DB::raw('SUM(lab_tests.price) as total_amount')
        )
            ->join('lab_tests', 'lab_orders.lab_test_id', '=', 'lab_tests.id')
            ->whereBetween('lab_orders.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->labTestId, fn($q) => $q->where('lab_orders.lab_test_id', $this->labTestId))
            ->when($this->priority, fn($q) => $q->where('lab_orders.priority', $this->priority))
            ->when($this->status, fn($q) => $q->where('lab_orders.payment_status', $this->status))
            ->when($this->processedBy, fn($q) => $q->where('lab_orders.paid_by', $this->processedBy))
            ->groupBy('lab_tests.id', 'lab_tests.name', 'lab_tests.code')
            ->get();
    }

    protected function getBaseQuery()
    {
        return LabOrder::with(['labTest', 'order.encounter.patient', 'paidByUser'])
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->labTestId, fn($q) => $q->where('lab_test_id', $this->labTestId))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->when($this->status, fn($q) => $q->where('payment_status', $this->status))
            ->when($this->processedBy, fn($q) => $q->where('paid_by', $this->processedBy));
    }

    public function getLabTestsProperty()
    {
        return LabTest::where('active', true)->orderBy('name')->get();
    }

    public function getProcessorsProperty()
    {
        // Get users who have processed lab payments (paid_by in lab_orders)
        $processorIds = LabOrder::whereNotNull('paid_by')
            ->distinct()
            ->pluck('paid_by')
            ->toArray();

        return User::whereIn('id', $processorIds)
            ->orWhereHas('processedPayments') // Keep this for card payments if needed
            ->get();
    }

    public function resetFilters()
    {
        $this->reset(['labTestId', 'priority', 'status', 'processedBy']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function render()
    {
        $orders = $this->getBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $this->calculateTotals();

        return view('livewire.report.lab-payment-report', [
            'orders' => $orders,
            'labTests' => $this->labTests,
            'processors' => $this->processors,
        ]);
    }
}
