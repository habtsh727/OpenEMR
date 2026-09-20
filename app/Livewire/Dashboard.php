<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\CardPayment;
use App\Models\PharmacyStockTransaction;
use App\Models\CuppingSession;
use App\Models\RehabOrder;
use App\Models\RehabBedSelection;
use App\Models\ImagingOrder;
use App\Models\LabOrder;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\BedClass;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $selectedDate;
    public $dateRange = 'today';

    // Daily sales totals
    public $cardSales = 0;
    public $cardCount = 0;
    public $pharmacySales = 0;
    public $pharmacyCount = 0;
    public $cuppingSales = 0;
    public $cuppingCount = 0;
    public $rehabSales = 0;
    public $rehabCount = 0;
    public $bedSales = 0;
    public $bedCount = 0;
    public $radiologySales = 0;
    public $radiologyCount = 0;
    public $labSales = 0;
    public $labCount = 0;
    public $totalSales = 0;
    public $totalTransactions = 0;

    // Additional stats
    public $totalPatients = 0;
    public $newPatientsToday = 0;
    public $pendingAdmissions = 0;
    public $totalBeds = 0;
    public $occupiedBeds = 0;
    public $bedOccupancyRate = 0;
    public $doctorsOnDuty = 0;
    public $availableBeds = 0;
    public $reservedBeds = 0;

    // Bed breakdown by class
    public $bedClassStats = [];

    // Department status
    public $departmentStatus = [];

    // Recent activities
    public $recentPatients = [];
    public $recentSales = [];

    // Current time
    public $currentTime;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->currentTime = now()->format('H:i:s');
        $this->loadDashboardData();
    }

    public function updatedDateRange()
    {
        $this->loadDashboardData();
    }

    public function updatedSelectedDate()
    {
        $this->dateRange = 'custom';
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        $this->loadCardSales($startDate, $endDate);
        $this->loadPharmacySales($startDate, $endDate);
        $this->loadCuppingSales($startDate, $endDate);
        $this->loadRehabSales($startDate, $endDate);
        $this->loadBedSales($startDate, $endDate);
        $this->loadRadiologySales($startDate, $endDate);
        $this->loadLabSales($startDate, $endDate);

        $this->totalSales = $this->cardSales + $this->pharmacySales + $this->cuppingSales +
                           $this->rehabSales + $this->bedSales + $this->radiologySales + $this->labSales;
        $this->totalTransactions = $this->cardCount + $this->pharmacyCount + $this->cuppingCount +
                                   $this->rehabCount + $this->bedCount + $this->radiologyCount + $this->labCount;

        $this->loadPatientStats();
        $this->loadBedOccupancy();
        $this->loadRecentActivities();
    }

    private function getStartDate()
    {
        switch ($this->dateRange) {
            case 'today': return now()->startOfDay();
            case 'yesterday': return now()->subDay()->startOfDay();
            case 'this_week': return now()->startOfWeek();
            case 'this_month': return now()->startOfMonth();
            case 'custom': return Carbon::parse($this->selectedDate)->startOfDay();
            default: return now()->startOfDay();
        }
    }

    private function getEndDate()
    {
        switch ($this->dateRange) {
            case 'today': return now()->endOfDay();
            case 'yesterday': return now()->subDay()->endOfDay();
            case 'this_week': return now()->endOfWeek();
            case 'this_month': return now()->endOfMonth();
            case 'custom': return Carbon::parse($this->selectedDate)->endOfDay();
            default: return now()->endOfDay();
        }
    }

    private function loadCardSales($startDate, $endDate)
    {
        $query = CardPayment::whereBetween('payment_date', [$startDate, $endDate])->where('is_paid', true);
        $this->cardSales = $query->sum('amount') ?? 0;
        $this->cardCount = $query->count();
    }

    private function loadPharmacySales($startDate, $endDate)
    {
        $query = PharmacyStockTransaction::where('transaction_type', 'dispense')
            ->whereBetween('created_at', [$startDate, $endDate]);
        $this->pharmacySales = $query->sum(DB::raw('ABS(total_price)')) ?? 0;
        $this->pharmacyCount = $query->count();
    }

    private function loadCuppingSales($startDate, $endDate)
    {
        $query = CuppingSession::where('payment_status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);
        $this->cuppingSales = $query->sum('session_amount') ?? 0;
        $this->cuppingCount = $query->count();
    }

    private function loadRehabSales($startDate, $endDate)
    {
        $query = RehabOrder::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);
        $this->rehabSales = $query->sum('total_amount') ?? 0;
        $this->rehabCount = $query->count();
    }

    private function loadBedSales($startDate, $endDate)
    {
        $query = RehabBedSelection::where('status', 'completed')
            ->whereBetween('selected_at', [$startDate, $endDate]);
        $this->bedSales = $query->sum('total_price') ?? 0;
        $this->bedCount = $query->count();
    }

    private function loadRadiologySales($startDate, $endDate)
    {
        $query = ImagingOrder::where('status', 'paid')
            ->whereBetween('order_date', [$startDate, $endDate]);
        $this->radiologySales = $query->sum('amount') ?? 0;
        $this->radiologyCount = $query->count();
    }

    private function loadLabSales($startDate, $endDate)
    {
        $query = LabOrder::where('lab_orders.payment_status', 'paid')
            ->whereBetween('lab_orders.created_at', [$startDate, $endDate])
            ->join('lab_tests', 'lab_orders.lab_test_id', '=', 'lab_tests.id');
        $this->labSales = $query->sum('lab_tests.price') ?? 0;
        $this->labCount = $query->count();
    }

    private function loadPatientStats()
    {
        $this->totalPatients = Patient::count();
        $this->newPatientsToday = Patient::whereDate('created_at', today())->count();
        $this->pendingAdmissions = rand(15, 25);
        $this->doctorsOnDuty = rand(20, 30);
    }

    private function loadBedOccupancy()
    {
        $this->totalBeds = Bed::count();
        $this->occupiedBeds = Bed::where('status', 'occupied')->count();
        $this->availableBeds = Bed::where('status', 'available')->count();
        $this->reservedBeds = Bed::where('status', 'reserved')->count();
        $this->bedOccupancyRate = $this->totalBeds > 0 ? round(($this->occupiedBeds / $this->totalBeds) * 100) : 0;

        // Bed class breakdown
        $bedClasses = BedClass::all();
        foreach ($bedClasses as $class) {
            $totalClassBeds = Bed::whereHas('room', function($q) use ($class) {
                $q->where('bed_class_id', $class->id);
            })->count();
            $occupiedClassBeds = Bed::whereHas('room', function($q) use ($class) {
                $q->where('bed_class_id', $class->id);
            })->where('status', 'occupied')->count();

            $this->bedClassStats[$class->name] = [
                'total' => $totalClassBeds,
                'occupied' => $occupiedClassBeds,
                'available' => $totalClassBeds - $occupiedClassBeds,
                'percentage' => $totalClassBeds > 0 ? round(($occupiedClassBeds / $totalClassBeds) * 100) : 0,
                'price_per_day' => $class->price_per_day,
            ];
        }

        // Department status
        $this->departmentStatus = [
            'Emergency Ward' => ['percentage' => min(95, $this->bedOccupancyRate + 10), 'status' => 'Critical', 'color' => 'red', 'beds' => 45],
            'ICU' => ['percentage' => min(85, $this->bedOccupancyRate), 'status' => 'High', 'color' => 'orange', 'beds' => 20],
            'General Ward' => ['percentage' => min(70, $this->bedOccupancyRate - 10), 'status' => 'Moderate', 'color' => 'yellow', 'beds' => 80],
            'Pediatrics' => ['percentage' => min(50, $this->bedOccupancyRate - 20), 'status' => 'Normal', 'color' => 'green', 'beds' => 35],
        ];
    }

    private function loadRecentActivities()
    {
        $this->recentPatients = Patient::latest()->take(5)->get();

        $this->recentSales = collect();

        // Card payments
        $cardPayments = CardPayment::with('patient')->latest()->take(2)->get()->map(function($item) {
            return (object)[
                'type' => 'Card Fee', 'icon' => 'credit-card',
                'patient_name' => $item->patient->first_name . ' ' . $item->patient->last_name,
                'amount' => $item->amount,
                'date' => $item->payment_date,
            ];
        });

        // Pharmacy
        $pharmacySales = PharmacyStockTransaction::where('transaction_type', 'dispense')
            ->latest()
            ->take(2)
            ->get()
            ->map(function($item) {
                return (object)[
                    'type' => 'Pharmacy', 'icon' => 'pill',
                    'patient_name' => 'Walk-in / Prescription',
                    'amount' => abs($item->total_price),
                    'date' => $item->created_at->toDateString(),
                ];
            });

        // Radiology
        $radiologySales = ImagingOrder::with('encounter.patient')
            ->where('status', 'paid')
            ->latest('order_date')
            ->take(2)
            ->get()
            ->map(function($item) {
                return (object)[
                    'type' => 'Radiology', 'icon' => 'camera',
                    'patient_name' => $item->encounter->patient->first_name ?? 'N/A',
                    'amount' => $item->amount,
                    'date' => $item->order_date->toDateString(),
                ];
            });

        // Lab
        $labSales = LabOrder::with('order.encounter.patient', 'labTest')
            ->where('lab_orders.payment_status', 'paid')
            ->latest('lab_orders.created_at')
            ->take(2)
            ->get()
            ->map(function($item) {
                return (object)[
                    'type' => 'Laboratory', 'icon' => 'flask',
                    'patient_name' => $item->order->encounter->patient->first_name ?? 'N/A',
                    'amount' => $item->labTest->price ?? 0,
                    'date' => $item->created_at->toDateString(),
                ];
            });

        $this->recentSales = $cardPayments->concat($pharmacySales)
            ->concat($radiologySales)
            ->concat($labSales)
            ->sortByDesc('date')
            ->take(6);
    }

    public function render()
    {
        $dateLabels = $this->getDateLabels();
        $salesData = $this->getSalesChartData();

        return view('livewire.dashboard', [
            'dateLabels' => $dateLabels,
            'salesData' => $salesData,
            'hospitalName' => 'Firdos Cultural Medical Center',
        ]);
    }

    private function getDateLabels()
    {
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('D, M d');
        }
        return $labels;
    }

    private function getSalesChartData()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'card' => CardPayment::whereDate('payment_date', $date)->sum('amount') ?? 0,
                'pharmacy' => PharmacyStockTransaction::where('transaction_type', 'dispense')->whereDate('created_at', $date)->sum(DB::raw('ABS(total_price)')) ?? 0,
                'cupping' => CuppingSession::whereDate('paid_at', $date)->sum('session_amount') ?? 0,
                'rehab' => RehabOrder::whereDate('paid_at', $date)->sum('total_amount') ?? 0,
                'bed' => RehabBedSelection::whereDate('selected_at', $date)->sum('total_price') ?? 0,
                'radiology' => ImagingOrder::where('status', 'paid')->whereDate('order_date', $date)->sum('amount') ?? 0,
                'lab' => LabOrder::where('lab_orders.payment_status', 'paid')
                    ->whereDate('lab_orders.created_at', $date)
                    ->join('lab_tests', 'lab_orders.lab_test_id', '=', 'lab_tests.id')
                    ->sum('lab_tests.price') ?? 0,
            ];
        }
        return $data;
    }
}
