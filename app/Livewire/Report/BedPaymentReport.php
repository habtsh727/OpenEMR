<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RehabBedSelection;
use App\Models\BedClass;
use App\Models\User;
use App\Models\RehabEncounter;
use Illuminate\Support\Facades\DB;

class BedPaymentReport extends Component
{
    use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $bedClassId = '';
    public $paymentMethod = '';
    public $status = '';
    public $doctorId = '';
    
    public $showFilters = false;
    public $totalSelections = 0;
    public $totalPaidSelections = 0;
    public $totalRevenue = 0;
    public $totalDays = 0;
    public $averagePricePerDay = 0;
    
    public $bedClassStats = [];
    public $dailyStats = [];
    public $paymentMethodStats = [];
    public $doctorStats = [];
    
    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'bedClassId' => ['except' => ''],
        'paymentMethod' => ['except' => ''],
        'status' => ['except' => ''],
        'doctorId' => ['except' => ''],
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
        
        $this->totalSelections = (clone $query)->count();
        $this->totalPaidSelections = (clone $query)->where('status', 'completed')->count();
        $this->totalRevenue = (clone $query)->where('status', 'completed')->sum('total_price') ?? 0;
        $this->totalDays = (clone $query)->where('status', 'completed')->sum('duration_days') ?? 0;
        
        $this->averagePricePerDay = $this->totalDays > 0 
            ? $this->totalRevenue / $this->totalDays 
            : 0;
        
        // Bed Class statistics
        $this->bedClassStats = DB::table('rehab_bed_selections')
            ->select(
                'bed_classes.name as bed_class_name',
                DB::raw('COUNT(*) as selection_count'),
                DB::raw('SUM(rehab_bed_selections.total_price) as total_amount'),
                DB::raw('SUM(rehab_bed_selections.duration_days) as total_days'),
                DB::raw('AVG(rehab_bed_selections.price_per_day) as avg_price_per_day')
            )
            ->join('rehab_orders', 'rehab_orders.id', '=', 'rehab_bed_selections.rehab_order_id')
            ->join('bed_classes', 'bed_classes.id', '=', 'rehab_bed_selections.bed_class_id')
            ->whereBetween('rehab_bed_selections.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_bed_selections.status', 'completed')
            ->when($this->bedClassId, fn($q) => $q->where('rehab_bed_selections.bed_class_id', $this->bedClassId))
            ->when($this->paymentMethod, fn($q) => $q->where('rehab_orders.payment_method', $this->paymentMethod))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy('bed_classes.name')
            ->get();
        
        // Daily statistics
        $this->dailyStats = DB::table('rehab_bed_selections')
            ->select(
                DB::raw('DATE(rehab_bed_selections.created_at) as date'),
                DB::raw('COUNT(*) as selection_count'),
                DB::raw('SUM(rehab_bed_selections.total_price) as total_amount'),
                DB::raw('SUM(rehab_bed_selections.duration_days) as total_days')
            )
            ->join('rehab_orders', 'rehab_orders.id', '=', 'rehab_bed_selections.rehab_order_id')
            ->whereBetween('rehab_bed_selections.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_bed_selections.status', 'completed')
            ->when($this->bedClassId, fn($q) => $q->where('rehab_bed_selections.bed_class_id', $this->bedClassId))
            ->when($this->paymentMethod, fn($q) => $q->where('rehab_orders.payment_method', $this->paymentMethod))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy(DB::raw('DATE(rehab_bed_selections.created_at)'))
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
        
        // Payment method statistics
        $this->paymentMethodStats = DB::table('rehab_orders')
            ->select(
                'rehab_orders.payment_method',
                DB::raw('COUNT(DISTINCT rehab_orders.id) as order_count'),
                DB::raw('SUM(rehab_bed_selections.total_price) as total_amount'),
                DB::raw('COUNT(rehab_bed_selections.id) as bed_count')
            )
            ->join('rehab_bed_selections', 'rehab_orders.id', '=', 'rehab_bed_selections.rehab_order_id')
            ->whereBetween('rehab_bed_selections.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_bed_selections.status', 'completed')
            ->whereNotNull('rehab_orders.payment_method')
            ->when($this->bedClassId, fn($q) => $q->where('rehab_bed_selections.bed_class_id', $this->bedClassId))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy('rehab_orders.payment_method')
            ->get()
            ->keyBy('payment_method');
        
        // Doctor statistics
        $this->doctorStats = DB::table('rehab_orders')
            ->select(
                'users.name as doctor_name',
                DB::raw('COUNT(DISTINCT rehab_orders.id) as order_count'),
                DB::raw('SUM(rehab_bed_selections.total_price) as total_amount'),
                DB::raw('COUNT(rehab_bed_selections.id) as bed_count'),
                DB::raw('SUM(rehab_bed_selections.duration_days) as total_days')
            )
            ->join('rehab_bed_selections', 'rehab_orders.id', '=', 'rehab_bed_selections.rehab_order_id')
            ->join('users', 'users.id', '=', 'rehab_orders.doctor_id')
            ->whereBetween('rehab_bed_selections.created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->where('rehab_bed_selections.status', 'completed')
            ->when($this->bedClassId, fn($q) => $q->where('rehab_bed_selections.bed_class_id', $this->bedClassId))
            ->when($this->paymentMethod, fn($q) => $q->where('rehab_orders.payment_method', $this->paymentMethod))
            ->when($this->doctorId, fn($q) => $q->where('rehab_orders.doctor_id', $this->doctorId))
            ->groupBy('users.name')
            ->get();
    }

    protected function getBaseQuery()
    {
        return RehabBedSelection::with([
                'rehabEncounter.encounter.patient',
                'rehabOrder.doctor',
                'bedClass',
                'bed.room.ward'
            ])
            ->whereBetween('created_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
            ->when($this->bedClassId, fn($q) => $q->where('bed_class_id', $this->bedClassId))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->paymentMethod || $this->doctorId, function($q) {
                $q->whereHas('rehabOrder', function($orderQuery) {
                    $orderQuery
                        ->when($this->paymentMethod, fn($q) => $q->where('payment_method', $this->paymentMethod))
                        ->when($this->doctorId, fn($q) => $q->where('doctor_id', $this->doctorId));
                });
            });
    }

    public function getBedClassesProperty()
    {
        return BedClass::orderBy('name')->get();
    }

    public function getDoctorsProperty()
    {
        $doctorIds = RehabBedSelection::whereHas('rehabOrder', fn($q) => $q->whereNotNull('doctor_id'))
            ->distinct()
            ->pluck('rehab_order_id')
            ->toArray();
        
        $orderIds = RehabBedSelection::whereIn('id', $doctorIds)->pluck('rehab_order_id');
        
        return User::whereIn('id', function($q) use ($orderIds) {
            $q->select('doctor_id')
              ->from('rehab_orders')
              ->whereIn('id', $orderIds);
        })->get();
    }

    public function resetFilters()
    {
        $this->reset(['bedClassId', 'paymentMethod', 'status', 'doctorId']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'selected' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400'
        };
    }

    public function render()
    {
        $selections = $this->getBaseQuery()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $this->calculateTotals();

        return view('livewire.report.bed-payment-report', [
            'selections' => $selections,
            'bedClasses' => $this->bedClasses,
            'doctors' => $this->doctors,
        ]);
    }
}