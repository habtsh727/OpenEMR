<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CuppingTherapy;
use App\Models\CuppingSession;
use App\Models\CuppingPayment;
use App\Models\CuppingTherapyPackage;
use Illuminate\Support\Facades\DB;

class CashierCuppingQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 10;

    public $showPaymentModal = false;
    public $selectedTherapy = null;
    public $unpaidSessions = [];
    public $selectedSessions = [];
    public $paymentMethod = 'cash';
    public $paymentAmount = 0;
    public $changeAmount = 0;
    public $transactionId = '';

    public $showSessionSelectionModal = false;
    public $sessionsList = [];
    public $selectAll = false;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $listeners = ['refreshQueue' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function processPayment($therapyId)
    {
        $this->selectedTherapy = CuppingTherapy::with([
            'encounter.patient',
            'sessions' => function ($query) {
                $query->orderBy('session_number');
            },
            'sessions.therapyPackage',
            'primaryPackage'
        ])->find($therapyId);

        if (!$this->selectedTherapy) {
            $this->showAlertMessage('Therapy not found', 'error');
            return;
        }

        $this->unpaidSessions = $this->selectedTherapy->sessions
            ->where('payment_status', '!=', 'paid')
            ->values();

        if ($this->unpaidSessions->isEmpty()) {
            $this->showAlertMessage('All sessions are already paid', 'warning');
            return;
        }

        $this->sessionsList = [];
        foreach ($this->unpaidSessions as $session) {
            $remainingAmount = $session->session_amount - $session->paid_amount;
            $this->sessionsList[] = [
                'id' => $session->id,
                'session_number' => $session->session_number,
                'session_date' => $session->session_date->format('Y-m-d'),
                'amount' => $session->session_amount,
                'paid_amount' => $session->paid_amount,
                'remaining' => $remainingAmount,
                'package_name' => $session->therapyPackage->package_name_snapshot ?? 'N/A',
                'selected' => false,
            ];
        }

        $this->selectAll = false;
        $this->showSessionSelectionModal = true;
    }

    public function updatedSelectAll()
    {
        foreach ($this->sessionsList as $index => $session) {
            $this->sessionsList[$index]['selected'] = $this->selectAll;
        }
        $this->calculateSelectedTotal();
    }

    public function toggleSessionSelection($index)
    {
        $this->sessionsList[$index]['selected'] = !$this->sessionsList[$index]['selected'];

        $allSelected = true;
        foreach ($this->sessionsList as $session) {
            if (!$session['selected']) {
                $allSelected = false;
                break;
            }
        }
        $this->selectAll = $allSelected;
        $this->calculateSelectedTotal();
    }

    public function calculateSelectedTotal()
    {
        $total = 0;
        $this->selectedSessions = [];

        foreach ($this->sessionsList as $session) {
            if ($session['selected']) {
                $total += $session['remaining'];
                $this->selectedSessions[] = $session['id'];
            }
        }

        $this->paymentAmount = $total;
        $this->changeAmount = 0;
    }

    public function proceedToPayment()
    {
        if (empty($this->selectedSessions)) {
            $this->showAlertMessage('Please select at least one session to pay', 'error');
            return;
        }

        $this->showSessionSelectionModal = false;
        $this->showPaymentModal = true;
    }

    public function updatedPaymentAmount()
    {
        $totalSelected = 0;
        foreach ($this->sessionsList as $session) {
            if (in_array($session['id'], $this->selectedSessions)) {
                $totalSelected += $session['remaining'];
            }
        }

        if ($this->paymentAmount > $totalSelected) {
            $this->changeAmount = $this->paymentAmount - $totalSelected;
        } else {
            $this->changeAmount = 0;
        }
    }

    public function confirmPayment()
    {
        if (empty($this->selectedSessions)) {
            $this->showAlertMessage('No sessions selected for payment', 'error');
            return;
        }

        $totalDue = 0;
        $selectedSessionsData = [];

        foreach ($this->sessionsList as $session) {
            if (in_array($session['id'], $this->selectedSessions)) {
                $totalDue += $session['remaining'];
                $selectedSessionsData[] = $session;
            }
        }

        if ($this->paymentAmount < $totalDue) {
            $this->showAlertMessage('Insufficient payment amount', 'error');
            return;
        }

        DB::beginTransaction();

        try {
            $amountPaid = 0;
            $remainingAmount = $this->paymentAmount;

            foreach ($selectedSessionsData as $sessionData) {
                if ($remainingAmount <= 0) break;

                $session = CuppingSession::find($sessionData['id']);
                if (!$session) continue;

                $sessionRemaining = $session->session_amount - $session->paid_amount;
                $paymentForSession = min($remainingAmount, $sessionRemaining);

                $newPaidAmount = $session->paid_amount + $paymentForSession;
                $isFullyPaid = $newPaidAmount >= $session->session_amount;

                // ONLY update this session's payment status
                $session->update([
                    'paid_amount' => $newPaidAmount,
                    'payment_status' => $isFullyPaid ? 'paid' : 'partial',
                    'paid_at' => $isFullyPaid ? now() : null,
                ]);

                CuppingPayment::create([
                    'cupping_session_id' => $session->id,
                    'cupping_therapy_id' => $this->selectedTherapy->id,
                    'amount' => $paymentForSession,
                    'payment_method' => $this->paymentMethod,
                    'received_by' => auth()->id(),
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                $amountPaid += $paymentForSession;
                $remainingAmount -= $paymentForSession;
            }

            // DO NOT update therapy status here - leave it as 'partial_paid'
            // Only mark as fully_paid if ALL sessions are paid
            $totalPaid = $this->selectedTherapy->sessions->sum('paid_amount');
            $totalAmount = $this->selectedTherapy->sessions->sum('session_amount');

            // Only change therapy status if ALL sessions are fully paid
            if ($totalPaid >= $totalAmount) {
                $this->selectedTherapy->update(['status' => 'fully_paid']);
            } elseif ($totalPaid > 0) {
                $this->selectedTherapy->update(['status' => 'partial_paid']);
            }

            DB::commit();

            $message = "✅ Payment processed successfully! Amount: ETB " . number_format($amountPaid, 2);
            $this->showAlertMessage($message, 'success');
            $this->closePaymentModal();
            $this->dispatch('refreshQueue');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment failed: ' . $e->getMessage());
            $this->showAlertMessage('Payment failed: ' . $e->getMessage(), 'error');
        }
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->showSessionSelectionModal = false;
        $this->selectedTherapy = null;
        $this->unpaidSessions = [];
        $this->selectedSessions = [];
        $this->sessionsList = [];
        $this->selectAll = false;
        $this->paymentMethod = 'cash';
        $this->paymentAmount = 0;
        $this->changeAmount = 0;
        $this->transactionId = '';
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        // Show therapies that have sessions needing payment
        $query = CuppingTherapy::with([
            'encounter.patient',
            'sessions',
            'sessions.therapyPackage',
            'primaryPackage'
        ])
            ->whereHas('sessions', function ($q) {
                $q->where('payment_status', '!=', 'paid');
            });

        if ($this->search) {
            $query->whereHas('encounter.patient', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('card_number', 'like', '%' . $this->search . '%');
            });
        }

        $therapies = $query->orderBy('created_at', 'asc')->paginate($this->perPage);

        foreach ($therapies as $therapy) {
            $totalAmount = $therapy->sessions->sum('session_amount');
            $totalPaid = $therapy->sessions->sum('paid_amount');
            $therapy->pending_amount = $totalAmount - $totalPaid;

            $therapyPackages = CuppingTherapyPackage::where('cupping_therapy_id', $therapy->id)->get();
            $therapy->groupedPackages = $therapyPackages->groupBy('cupping_package_id');
        }

        return view('livewire.cupping.cashier-cupping-queue', [
            'therapies' => $therapies,
        ]);
    }
}
