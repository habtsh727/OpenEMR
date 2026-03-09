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
    public $reportType = 'daily';
    public $dateFrom;
    public $dateTo;
    public $selectedPatient = '';
    public $selectedDoctor = '';
    public $paymentTypeFilter = 'all';
    public $paymentStatusFilter = 'all';
    
    // Collapsible sections
    public $expandedSections = [
        'daily' => true,
        'monthly' => true,
        'full_payments' => true,
        'installment_payments' => true,
        'outstanding' => true,
        'patient_summary' => true
    ];
    
    // Statistics
    public $totalCollected = 0;
    public $totalOrders = 0;
    public $totalInstallments = 0;
    public $fullPaymentsCount = 0;
    public $installmentOrdersCount = 0;
    public $overdueCount = 0;
    public $pendingCount = 0;
    public $partialCount = 0;
    
    // Summary data
    public $paymentMethodSummary = [];
    public $dailyTotals = [];
    public $monthlyTotals = [];
    
    // Patients and Doctors lists
    public $patients = [];
    public $doctors = [];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        
        $this->loadFilterData();
        $this->calculateStatistics();
    }

    public function toggleSection($section)
    {
        $this->expandedSections[$section] = !$this->expandedSections[$section];
    }

    public function expandAll()
    {
        foreach ($this->expandedSections as $key => $value) {
            $this->expandedSections[$key] = true;
        }
    }

    public function collapseAll()
    {
        foreach ($this->expandedSections as $key => $value) {
            $this->expandedSections[$key] = false;
        }
    }

    public function loadFilterData()
    {
        $this->patients = RehabOrder::with('encounter.encounter.patient')
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

        $this->doctors = User::whereHas('rehabOrders', function($q) {})
            ->get(['id', 'name'])
            ->map(function($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name
                ];
            })
            ->toArray();
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

    public function updatedSelectedPatient()
    {
        $this->calculateStatistics();
    }

    public function updatedSelectedDoctor()
    {
        $this->calculateStatistics();
    }

    public function updatedPaymentTypeFilter()
    {
        $this->calculateStatistics();
    }

    public function updatedPaymentStatusFilter()
    {
        $this->calculateStatistics();
    }

    public function calculateStatistics()
    {
        // Base query for orders
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

        if ($this->paymentTypeFilter && $this->paymentTypeFilter !== 'all') {
            $ordersQuery->where('payment_type', $this->paymentTypeFilter);
        }

        if ($this->paymentStatusFilter && $this->paymentStatusFilter !== 'all') {
            $ordersQuery->where('payment_status', $this->paymentStatusFilter);
        }
        
        // Get paid installments (only fully paid installments count as payments)
        $paidInstallmentsQuery = RehabPaymentInstallment::whereBetween('paid_date', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->where('status', 'paid');
            
        if ($this->selectedPatient) {
            $paidInstallmentsQuery->whereHas('rehabOrder.encounter.encounter.patient', function($q) {
                $q->where('id', $this->selectedPatient);
            });
        }
        
        if ($this->selectedDoctor) {
            $paidInstallmentsQuery->whereHas('rehabOrder', function($q) {
                $q->where('doctor_id', $this->selectedDoctor);
            });
        }
        
        // Calculate totals
        $this->totalCollected = $paidInstallmentsQuery->sum('paid_amount');
        $this->totalOrders = $ordersQuery->count();
        $this->totalInstallments = $paidInstallmentsQuery->count();
        
        // Payment type counts - based on actual order payment_type
        $this->fullPaymentsCount = (clone $ordersQuery)->where('payment_type', 'full')->count();
        $this->installmentOrdersCount = (clone $ordersQuery)->where('payment_type', 'installment')->count();
        
        // Status counts based on actual data
        $baseInstallmentQuery = RehabPaymentInstallment::whereHas('rehabOrder', function($q) {
            $q->whereBetween('created_at', [$this->dateFrom, $this->dateTo . ' 23:59:59']);
            if ($this->selectedPatient) {
                $q->whereHas('encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            }
            if ($this->selectedDoctor) {
                $q->where('doctor_id', $this->selectedDoctor);
            }
        });
        
        $this->overdueCount = (clone $baseInstallmentQuery)->where('status', 'overdue')->count();
        $this->pendingCount = (clone $baseInstallmentQuery)->where('status', 'pending')->count();
        $this->partialCount = (clone $baseInstallmentQuery)->where('status', 'partial')->count();
        
        // Payment method summary - from paid installments
        $this->paymentMethodSummary = [
            'cash' => (clone $paidInstallmentsQuery)->whereHas('rehabOrder', function($q) {
                $q->where('payment_method', 'cash');
            })->sum('paid_amount'),
            'card' => (clone $paidInstallmentsQuery)->whereHas('rehabOrder', function($q) {
                $q->where('payment_method', 'card');
            })->sum('paid_amount'),
            'insurance' => (clone $paidInstallmentsQuery)->whereHas('rehabOrder', function($q) {
                $q->where('payment_method', 'insurance');
            })->sum('paid_amount'),
            'bank_transfer' => (clone $paidInstallmentsQuery)->whereHas('rehabOrder', function($q) {
                $q->where('payment_method', 'bank_transfer');
            })->sum('paid_amount'),
        ];
        
        $this->calculateDailyTotals();
        $this->calculateMonthlyTotals();
    }

    public function calculateDailyTotals()
    {
        $start = Carbon::parse($this->dateFrom);
        $end = Carbon::parse($this->dateTo);
        $days = min($start->diffInDays($end) + 1, 31);
        
        $this->dailyTotals = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            
            // Get fully paid installments on this date (these are actual payments)
            $paidInstallments = RehabPaymentInstallment::whereDate('paid_date', $date)
                ->where('status', 'paid')
                ->when($this->selectedPatient, function($q) {
                    $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                        $pq->where('id', $this->selectedPatient);
                    });
                })
                ->when($this->selectedDoctor, function($q) {
                    $q->whereHas('rehabOrder', function($oq) {
                        $oq->where('doctor_id', $this->selectedDoctor);
                    });
                })
                ->get();
            
            // Separate by payment type of the parent order
            $fullPayments = $paidInstallments->filter(function($inst) {
                return $inst->rehabOrder->payment_type === 'full';
            })->sum('paid_amount');
            
            $installmentPayments = $paidInstallments->filter(function($inst) {
                return $inst->rehabOrder->payment_type === 'installment';
            })->sum('paid_amount');
            
            $this->dailyTotals[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('M d'),
                'full' => $fullPayments,
                'installment' => $installmentPayments,
                'total' => $fullPayments + $installmentPayments
            ];
        }
    }

    public function calculateMonthlyTotals()
    {
        $this->monthlyTotals = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            // Get paid installments in this month
            $paidInstallments = RehabPaymentInstallment::whereBetween('paid_date', [$startOfMonth, $endOfMonth])
                ->where('status', 'paid')
                ->when($this->selectedPatient, function($q) {
                    $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                        $pq->where('id', $this->selectedPatient);
                    });
                })
                ->when($this->selectedDoctor, function($q) {
                    $q->whereHas('rehabOrder', function($oq) {
                        $oq->where('doctor_id', $this->selectedDoctor);
                    });
                })
                ->get();
            
            $fullPayments = $paidInstallments->filter(function($inst) {
                return $inst->rehabOrder->payment_type === 'full';
            })->sum('paid_amount');
            
            $installmentPayments = $paidInstallments->filter(function($inst) {
                return $inst->rehabOrder->payment_type === 'installment';
            })->sum('paid_amount');
            
            $this->monthlyTotals[] = [
                'month' => $month->format('Y-m'),
                'label' => $month->format('M Y'),
                'full' => $fullPayments,
                'installment' => $installmentPayments,
                'total' => $fullPayments + $installmentPayments
            ];
        }
    }

    public function getFullPaymentReport()
    {
        // Get all paid installments from full payment orders
        return RehabPaymentInstallment::with([
                'rehabOrder.encounter.encounter.patient',
                'rehabOrder.encounter.encounter.doctor'
            ])
            ->whereHas('rehabOrder', function($q) {
                $q->where('payment_type', 'full');
            })
            ->whereBetween('paid_date', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->where('status', 'paid')
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->when($this->selectedDoctor, function($q) {
                $q->whereHas('rehabOrder', function($oq) {
                    $oq->where('doctor_id', $this->selectedDoctor);
                });
            })
            ->orderBy('paid_date', 'desc')
            ->paginate(15, pageName: 'full_page');
    }

    public function getInstallmentPaymentReport()
    {
        // Get all paid installments from installment orders
        return RehabPaymentInstallment::with([
                'rehabOrder.encounter.encounter.patient',
                'rehabOrder.encounter.encounter.doctor'
            ])
            ->whereHas('rehabOrder', function($q) {
                $q->where('payment_type', 'installment');
            })
            ->whereBetween('paid_date', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->where('status', 'paid')
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->when($this->selectedDoctor, function($q) {
                $q->whereHas('rehabOrder', function($oq) {
                    $oq->where('doctor_id', $this->selectedDoctor);
                });
            })
            ->orderBy('paid_date', 'desc')
            ->paginate(15, pageName: 'installment_page');
    }

    public function getOutstandingReport()
    {
        // Get all installments that are not fully paid (pending, partial, overdue)
        return RehabPaymentInstallment::with([
                'rehabOrder.encounter.encounter.patient',
                'rehabOrder.encounter.encounter.doctor'
            ])
            ->whereHas('rehabOrder', function($q) {
                $q->where('payment_type', 'installment');
            })
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('rehabOrder.encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->when($this->selectedDoctor, function($q) {
                $q->whereHas('rehabOrder', function($oq) {
                    $oq->where('doctor_id', $this->selectedDoctor);
                });
            })
            ->orderBy('due_date')
            ->paginate(15, pageName: 'outstanding_page');
    }

    public function getPatientSummary()
    {
        return RehabOrder::with([
                'encounter.encounter.patient', 
                'paymentInstallments'
            ])
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo . ' 23:59:59'])
            ->when($this->selectedPatient, function($q) {
                $q->whereHas('encounter.encounter.patient', function($pq) {
                    $pq->where('id', $this->selectedPatient);
                });
            })
            ->when($this->selectedDoctor, function($q) {
                $q->where('doctor_id', $this->selectedDoctor);
            })
            ->get()
            ->groupBy('encounter.encounter.patient.id')
            ->map(function($orders, $patientId) {
                $patient = $orders->first()->encounter->encounter->patient;
                
                $totalPaid = 0;
                $totalFullPayments = 0;
                $totalInstallmentOrders = 0;
                $totalInstallments = 0;
                $paidInstallments = 0;
                $pendingInstallments = 0;
                $overdueInstallments = 0;
                $totalDue = 0;
                
                foreach ($orders as $order) {
                    if ($order->payment_type === 'full') {
                        $totalFullPayments++;
                        // For full payments, the paid amount is from the single installment
                        $paidInstallment = $order->paymentInstallments->first();
                        if ($paidInstallment && $paidInstallment->status === 'paid') {
                            $totalPaid += $paidInstallment->paid_amount;
                        }
                    } else {
                        $totalInstallmentOrders++;
                        foreach ($order->paymentInstallments as $installment) {
                            $totalInstallments++;
                            if ($installment->status === 'paid') {
                                $paidInstallments++;
                                $totalPaid += $installment->paid_amount;
                            } elseif ($installment->status === 'pending') {
                                $pendingInstallments++;
                                $totalDue += $installment->getRemainingAmount();
                            } elseif ($installment->status === 'partial') {
                                $pendingInstallments++;
                                $totalDue += $installment->getRemainingAmount();
                            } elseif ($installment->status === 'overdue') {
                                $overdueInstallments++;
                                $totalDue += $installment->getRemainingAmount();
                            }
                        }
                    }
                }
                
                return [
                    'patient_id' => $patientId,
                    'patient_name' => $patient->name,
                    'total_orders' => $orders->count(),
                    'total_paid' => $totalPaid,
                    'total_due' => $totalDue,
                    'full_payments' => $totalFullPayments,
                    'installment_orders' => $totalInstallmentOrders,
                    'total_installments' => $totalInstallments,
                    'paid_installments' => $paidInstallments,
                    'pending_installments' => $pendingInstallments,
                    'overdue_installments' => $overdueInstallments,
                    'status' => $totalDue > 0 ? 'Has Balance' : 'Fully Paid'
                ];
            })
            ->values();
    }

    public function exportToCsv()
    {
        $fullPayments = $this->getFullPaymentReport();
        $installmentPayments = $this->getInstallmentPaymentReport();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payment-report-' . now()->format('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($fullPayments, $installmentPayments) {
            $file = fopen('php://output', 'w');
            
            // Full Payments Section
            fputcsv($file, ['FULL PAYMENTS']);
            fputcsv($file, ['Date', 'Order #', 'Patient', 'Doctor', 'Amount', 'Method']);
            
            foreach ($fullPayments as $payment) {
                fputcsv($file, [
                    $payment->paid_date->format('Y-m-d'),
                    $payment->rehab_order_id,
                    $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A',
                    $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A',
                    $payment->paid_amount,
                    $payment->rehabOrder->payment_method ?? 'N/A'
                ]);
            }
            
            fputcsv($file, []);
            
            // Installment Payments Section
            fputcsv($file, ['INSTALLMENT PAYMENTS']);
            fputcsv($file, ['Date', 'Order #', 'Installment #', 'Patient', 'Doctor', 'Amount', 'Method']);
            
            foreach ($installmentPayments as $payment) {
                fputcsv($file, [
                    $payment->paid_date->format('Y-m-d'),
                    $payment->rehab_order_id,
                    $payment->installment_number,
                    $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A',
                    $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A',
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
        $fullPayments = $this->getFullPaymentReport();
        $installmentPayments = $this->getInstallmentPaymentReport();
        $outstandingReport = $this->getOutstandingReport();
        $patientSummary = $this->getPatientSummary();
        
        return view('livewire.rehab.cashier.payment-reports', [
            'fullPayments' => $fullPayments,
            'installmentPayments' => $installmentPayments,
            'outstandingReport' => $outstandingReport,
            'patientSummary' => $patientSummary
        ]);
    }
}