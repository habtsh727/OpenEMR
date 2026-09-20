<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RehabOrder;
use App\Models\RehabPackage;
use App\Models\RehabBedSelection;
use App\Models\User;
use App\Models\RehabEncounter;
use Illuminate\Support\Facades\DB;

class RehabPaymentReport extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $packageId = '';
    public $paymentMethod = '';
    public $status = '';
    public $doctorId = '';
    public $bedClassId = '';
    
    public $showFilters = false;
    public $totalAmount = 0;
    public $totalOrders = 0;
    public $totalPaidOrders = 0;
    public $totalPackageRevenue = 0;
    public $totalBedRevenue = 0;
    
    public $packageStats = [];
    public $bedClassStats = [];
    public $itemTypeStats = [];
    public $paymentMethodStats = [];
    
    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'packageId' => ['except' => ''],
        'paymentMethod' => ['except' => ''],
        'status' => ['except' => ''],
        'doctorId' => ['except' => ''],
        'bedClassId' => ['except' => ''],
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
        
        $this->totalOrders = (clone $query)->count();
        $this->totalPaidOrders = (clone $query)->where('status', 'paid')->count();
        
        // Calculate package revenue (from rehab_orders)
        $this->totalPackageRevenue = (clone $query)
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;
        
        // Calculate bed revenue (from rehab_bed_selections)
        $this->totalBedRevenue = RehabBedSelection::query()
            ->whereHas('rehabOrder', function($q) {
                $q->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                    ->where('status', 'paid')
                    ->when($this->paymentMethod, fn($q) => $q->where('payment_method', $this->paymentMethod))
                    ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId));
            })
            ->when($this->bedClassId, fn($q) => $q->where('bed_class_id', $this->bedClassId))
            ->sum('total_price') ?? 0;
        
        $this->totalAmount = $this->totalPackageRevenue + $this->totalBedRevenue;
        
        // Package statistics
        $this->packageStats = DB::table('rehab_order_packages')
            ->select(
                'rehab_order_packages.package_name',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(rehab_order_packages.final_price) as total_amount')
            )
            ->join('rehab_orders', 'rehab_orders.id', '=', 'rehab_order_packages.rehab_order_id')
            ->whereBetween('rehab_orders.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_orders.status', 'paid')
            ->when($this->packageId, fn($q) => $q->where('rehab_order_packages.rehab_package_id', $this->packageId))
            ->when($this->paymentMethod, fn($q) => $q->where('rehab_orders.payment_method', $this->paymentMethod))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy('rehab_order_packages.package_name')
            ->get();
        
        // Bed Class statistics
        $this->bedClassStats = DB::table('rehab_bed_selections')
            ->select(
                'bed_classes.name as bed_class_name',
                DB::raw('COUNT(*) as selection_count'),
                DB::raw('SUM(rehab_bed_selections.total_price) as total_amount'),
                DB::raw('SUM(rehab_bed_selections.duration_days) as total_days')
            )
            ->join('rehab_orders', 'rehab_orders.id', '=', 'rehab_bed_selections.rehab_order_id')
            ->join('bed_classes', 'bed_classes.id', '=', 'rehab_bed_selections.bed_class_id')
            ->whereBetween('rehab_orders.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_orders.status', 'paid')
            ->when($this->bedClassId, fn($q) => $q->where('rehab_bed_selections.bed_class_id', $this->bedClassId))
            ->when($this->paymentMethod, fn($q) => $q->where('rehab_orders.payment_method', $this->paymentMethod))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy('bed_classes.name')
            ->get();
        
        // Item type statistics (medications, services)
        $this->itemTypeStats = DB::table('rehab_order_items')
            ->select(
                'rehab_order_items.item_type',
                DB::raw('COUNT(*) as item_count'),
                DB::raw('SUM(rehab_order_items.total_price) as total_amount')
            )
            ->join('rehab_order_packages', 'rehab_order_packages.id', '=', 'rehab_order_items.rehab_order_package_id')
            ->join('rehab_orders', 'rehab_orders.id', '=', 'rehab_order_packages.rehab_order_id')
            ->whereBetween('rehab_orders.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_orders.status', 'paid')
            ->whereIn('rehab_order_items.item_type', ['standard_medication', 'custom_medication', 'service'])
            ->when($this->paymentMethod, fn($q) => $q->where('rehab_orders.payment_method', $this->paymentMethod))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy('rehab_order_items.item_type')
            ->get()
            ->keyBy('item_type');
        
        // Payment method statistics
        $this->paymentMethodStats = (clone $query)
            ->where('status', 'paid')
            ->whereNotNull('payment_method')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get()
            ->keyBy('payment_method')
            ->toArray();
    }

    protected function getBaseQuery()
    {
        return RehabOrder::with([
                'rehabEncounter.encounter.patient',
                'doctor',
                'packages.items',
                'bedSelections.bedClass'
            ])
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->packageId, function($q) {
                $q->whereHas('packages', function($packageQuery) {
                    $packageQuery->where('rehab_package_id', $this->packageId);
                });
            })
            ->when($this->bedClassId, function($q) {
                $q->whereHas('bedSelections', function($bedQuery) {
                    $bedQuery->where('bed_class_id', $this->bedClassId);
                });
            })
            ->when($this->paymentMethod, fn($q) => $q->where('payment_method', $this->paymentMethod))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId));
    }

    public function getPackagesProperty()
    {
        return RehabPackage::where('is_active', true)->orderBy('name')->get();
    }

    public function getBedClassesProperty()
    {
        return DB::table('bed_classes')->orderBy('name')->get();
    }

    public function getDoctorsProperty()
    {
        $doctorIds = RehabOrder::whereNotNull('doctor_id')
            ->distinct()
            ->pluck('doctor_id')
            ->toArray();
        
        return User::whereIn('id', $doctorIds)->get();
    }

    public function resetFilters()
    {
        $this->reset(['packageId', 'paymentMethod', 'status', 'doctorId', 'bedClassId']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function getItemTypeIcon($type)
    {
        return match($type) {
            'standard_medication' => '💊',
            'custom_medication' => '⚗️',
            'service' => '🩺',
            'bed' => '🛏️',
            default => '📋'
        };
    }

    public function getItemTypeColor($type)
    {
        return match($type) {
            'standard_medication' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'custom_medication' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'service' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'bed' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400'
        };
    }

    public function render()
    {
        $orders = $this->getBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        // Calculate bed cost for each order
        foreach ($orders as $order) {
            $order->bed_total = $order->bedSelections->sum('total_price') ?? 0;
            $order->grand_total = $order->total_amount + $order->bed_total;
        }
            
        $this->calculateTotals();

        return view('livewire.report.rehab-payment-report', [
            'orders' => $orders,
            'packages' => $this->packages,
            'bedClasses' => $this->bedClasses,
            'doctors' => $this->doctors,
        ]);
    }
}