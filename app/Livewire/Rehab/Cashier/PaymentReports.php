<?php
// app/Livewire/Rehab/Cashier/PaymentReports.php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabPaymentInstallment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentReports extends Component
{
    use WithPagination;

    // Report type and filters
    public $reportType = 'daily'; // daily, monthly, patient, installment, method, doctor
    public $dateFrom;
    public $dateTo;
    public $selectedPatient = '';
    public $selectedDoctor = '';
    public $paymentMethod = '';
    public $status = '';
    
    // Statistics
    public $totalCollected = 0;
    public $totalOrders = 0;
    public $totalInstallments = 0;
    public $overdueCount = 0;
    public $pendingCount = 0;
    
    // Charts data
    public $chartLabels = [];
    public $chartData = [];
    
    // Patients and Doctors lists for dropdowns
    public $patients = [];
    public $doctors = [];

    public function mount()
    {
        // Set default date range (last 30 days)
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        
        // Load patients and doctors for filters
        $this->loadFilterData();
        $this->calculateStatistics();
    }

    public function loadFilterData()
    {
        // Get unique patients with payments
        $this->patients = RehabOrder::with('encounter.encounter.patient')
            ->whereHas('paymentInstallments', function($q) {
                $q->where('paid_amount', '>', 0);
            })
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->encounter->encounter->patient->id,
                    'name' => $order->encounter->encounter->patient->name
                ];
            })
            ->unique('id')
            ->values()
            ->toArray();

        // Get doctors
        $this->doctors = User::whereHas('rehabOrders')->get(['id', 'name'])->toArray();
    }

    public function updatedReportType()
    {
        $this->resetPage();
        $this->calculateStatistics();
    }

    public function updatedDateFrom()
    {
        $this->calculateStatistics();
    }

    public function updatedDateTo()
    {
        $this->calculateStatistics();
    }

    public function calculateStatistics()
    {
        // Base query for orders in date range
        $ordersQuery = RehabOrder::whereBetween('created_at', [$this->dateFrom, $this->dateTo . ' 23:59:59']);
        
        // Apply filters
        if ($this->selectedPatient) {
            $ordersQuery->whereHas('encounter.encounter.patient', function($q) {
                $q->where('id', $this->selectedPatient);
            });
        }
        
        if ($this->selectedDoctor) {
            $ordersQuery->where('doctor_id', $this->selectedDoctor);
        }
        
        // Get paid installments in date range
        $installmentsQuery = RehabPaymentInstallment::whereBetween('paid_date', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->where('status', 'paid');
            
        if ($this->selectedPatient) {
            $installmentsQuery->whereHas('rehabOrder.encounter.encounter.patient', function($q) {
                $q->where('id', $this->selectedPatient);
            });
        }
        
        // Calculate totals
        $this->totalCollected = $installmentsQuery->sum('paid_amount');
        $this->totalOrders = $ordersQuery->count();
        $this->totalInstallments = $installmentsQuery->count();
        
        // Overdue and pending counts
        $this->overdueCount = RehabPaymentInstallment::where('status', 'overdue')
            ->whereHas('rehabOrder', function($q) {
                if ($this->selectedPatient) {
                    $q->whereHas('encounter.encounter.patient', function($pq) {
                        $pq->where('id', $this->selectedPatient);
                    });
                }
            })
            ->count();
            
        $this->pendingCount = RehabPaymentInstallment::where('status', 'pending')
            ->whereHas('rehabOrder', function($q) {
                if ($this->selectedPatient) {
                    $q->whereHas('encounter.encounter.patient', function($pq) {
                        $pq->where('id', $this->selectedPatient);
                    });
                }
            })
            ->count();
            
        // Generate chart data based on report type
        $this->generateChartData();
    }

    public function generateChartData()
    {
        switch ($this->reportType) {
            case 'daily':
                $this->generateDailyChart();
                break;
            case 'monthly':
                $this->generateMonthlyChart();
                break;
            case 'method':
                $this->generatePaymentMethodChart();
                break;
            case 'doctor':
                $this->generateDoctorChart();
                break;
        }
    }

    public function generateDailyChart()
    {
        $start = Carbon::parse($this->dateFrom);
        $end = Carbon::parse($this->dateTo);
        $days = $start->diffInDays($end) + 1;
        
        $this->chartLabels = [];
        $this->chartData = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $this->chartLabels[] = $date->format('M d');
            
            $total = RehabPaymentInstallment::whereDate('paid_date', $date)
                ->where('status', 'paid')
                ->sum('paid_amount');
                
            $this->chartData[] = round($total, 2);
        }
    }

    public function generateMonthlyChart()
    {
        $this->chartLabels = [];
        $this->chartData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $this->chartLabels[] = $month->format('M Y');
            
            $total = RehabPaymentInstallment::whereYear('paid_date', $month->year)
                ->whereMonth('paid_date', $month->month)
                ->where('status', 'paid')
                ->sum('paid_amount');
                
            $this->chartData[] = round($total, 2);
        }
    }

    public function generatePaymentMethodChart()
    {
        $methods = ['cash', 'card', 'insurance', 'bank_transfer'];
        $this->chartLabels = [];
        $this->chartData = [];
        
        foreach ($methods as $method) {
            $this->chartLabels[] = ucfirst($method);
            
            $total = RehabOrder::where('payment_method', $method)
                ->whereBetween('paid_at', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
                ->sum('paid_amount');
                
            $this->chartData[] = round($total, 2);
        }
    }

    public function generateDoctorChart()
    {
        $topDoctors = User::whereHas('rehabOrders', function($q) {
                $q->whereBetween('paid_at', [$this->dateFrom, $this->dateTo . ' 23:59:59']);
            })
            ->withSum(['rehabOrders as total' => function($q) {
                $q->whereBetween('paid_at', [$this->dateFrom, $this->dateTo . ' 23:59:59']);
            }], 'paid_amount')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
            
        $this->chartLabels = $topDoctors->pluck('name')->toArray();
        $this->chartData = $topDoctors->pluck('total')->map(function($val) {
            return round($val ?? 0, 2);
        })->toArray();
    }

    public function getDailyReport()
    {
        return RehabPaymentInstallment::with(['rehabOrder.encounter.encounter.patient', 'rehabOrder.encounter.encounter.doctor'])
            ->whereBetween('paid_date', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->where('status', 'paid')
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->when($this->selectedDoctor, function($q) {
                $q->whereHas('rehabOrder.encounter.encounter.doctor', function($dq) {
                    $dq->where('id', $this->selectedDoctor);
                });
            })
            ->orderBy('paid_date', 'desc')
            ->paginate(15);
    }

    public function getInstallmentReport()
    {
        return RehabPaymentInstallment::with(['rehabOrder.encounter.encounter.patient'])
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->orderBy('due_date')
            ->paginate(15);
    }

    public function getPatientSummary()
    {
        return RehabOrder::with(['encounter.encounter.patient', 'paymentInstallments'])
            ->whereHas('paymentInstallments')
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->get()
            ->groupBy('encounter.encounter.patient.id')
            ->map(function($orders, $patientId) {
                $patient = $orders->first()->encounter->encounter->patient;
                $totalPaid = $orders->sum('paid_amount');
                $totalDue = $orders->sum(function($order) {
                    return ($order->grand_total ?? $order->total_amount) - $order->paid_amount;
                });
                
                return [
                    'patient_id' => $patientId,
                    'patient_name' => $patient->name,
                    'total_orders' => $orders->count(),
                    'total_paid' => $totalPaid,
                    'total_due' => $totalDue,
                    'status' => $totalDue > 0 ? 'Has Balance' : 'Fully Paid'
                ];
            })
            ->values();
    }

    public function exportToCsv()
    {
        $data = $this->getDailyReport();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payment-report-' . now()->format('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Date', 'Order #', 'Patient', 'Doctor', 'Installment #', 'Amount', 'Method']);
            
            // Rows
            foreach ($data as $payment) {
                fputcsv($file, [
                    $payment->paid_date->format('Y-m-d'),
                    $payment->rehab_order_id,
                    $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A',
                    $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A',
                    $payment->installment_number,
                    $payment->paid_amount,
                    $payment->rehabOrder->payment_method ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $dailyReport = $this->getDailyReport();
        $installmentReport = $this->getInstallmentReport();
        $patientSummary = $this->getPatientSummary();
        
        return view('livewire.rehab.cashier.payment-reports', [
            'dailyReport' => $dailyReport,
            'installmentReport' => $installmentReport,
            'patientSummary' => $patientSummary
        ]);
    }
}