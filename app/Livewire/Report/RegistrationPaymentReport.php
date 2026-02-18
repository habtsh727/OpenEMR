<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CardPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationPaymentReport extends Component
{
      use WithPagination;

    public $dateFrom;
    public $dateTo;
    public $paymentType = '';
    public $processedBy = '';
    public $status = '';
    
    public $showFilters = false;
    public $totalAmount = 0;
    public $totalPayments = 0;
    public $paymentTypeStats = [];
    
    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'paymentType' => ['except' => ''],
        'processedBy' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updated($property)
    {
        $this->resetPage();
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $query = $this->getBaseQuery();
        
        $this->totalAmount = $query->sum('amount');
        $this->totalPayments = $query->count();
        
        $this->paymentTypeStats = CardPayment::select('payment_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->whereBetween('payment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->paymentType, fn($q) => $q->where('payment_type', $this->paymentType))
            ->when($this->processedBy, fn($q) => $q->where('processed_by', $this->processedBy))
            ->when($this->status !== '', fn($q) => $q->where('is_paid', $this->status === 'paid'))
            ->groupBy('payment_type')
            ->get()
            ->keyBy('payment_type')
            ->toArray();
    }

    protected function getBaseQuery()
    {
        return CardPayment::with(['patient', 'processor'])
            ->whereBetween('payment_date', [$this->dateFrom, $this->dateTo])
            ->when($this->paymentType, fn($q) => $q->where('payment_type', $this->paymentType))
            ->when($this->processedBy, fn($q) => $q->where('processed_by', $this->processedBy))
            ->when($this->status !== '', fn($q) => $q->where('is_paid', $this->status === 'paid'));
    }

    public function getProcessorsProperty()
    {
        return User::whereHas('processedPayments')->get();
    }

    public function resetFilters()
    {
        $this->reset(['paymentType', 'processedBy', 'status']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function exportToExcel()
    {
        $payments = $this->getBaseQuery()->get();
        
        // You'll need to install: composer require maatwebsite/excel
        // return Excel::download(new PaymentsExport($payments), 'registration-payments.xlsx');
        
        // For now, we'll just show a message
        session()->flash('message', 'Excel export will be implemented with maatwebsite/excel package');
    }

    public function exportToPDF()
    {
        $payments = $this->getBaseQuery()->get();
        $totalAmount = $this->totalAmount;
        $dateRange = $this->dateFrom . ' to ' . $this->dateTo;
        
        // You'll need to install: composer require barryvdh/laravel-dompdf
        // $pdf = Pdf::loadView('exports.registration-payments-pdf', compact('payments', 'totalAmount', 'dateRange'));
        // return $pdf->download('registration-payments.pdf');
        
        session()->flash('message', 'PDF export will be implemented with barryvdh/laravel-dompdf package');
    }

    public function render()
    {
        $payments = $this->getBaseQuery()
            ->orderBy('payment_date', 'desc')
            ->paginate(15);
            
        $this->calculateTotals();

        return view('livewire.report.registration-payment-report', [
            'payments' => $payments,
            'processors' => $this->processors,
        ]);
    }
}
