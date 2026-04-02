<?php

namespace App\Livewire\Cashier;

use Livewire\Component;
use App\Models\CuppingSession;
use App\Models\CuppingQueue;
use App\Models\CuppingPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuppingPaymentForm extends Component
{
    public $session;
    public $amount = 0;
    public $payment_method = 'cash';
    public $remaining = 0;
    public $session_amount = 0;
    public $paid_amount = 0;
    
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'amount' => 'required|numeric|min:0.01',
        'payment_method' => 'required|in:cash,card,bank_transfer,mobile_money',
    ];

    public function mount($session)
    {
        $this->session = $session;
        $this->session_amount = $session->session_amount;
        $this->paid_amount = $session->paid_amount;
        $this->remaining = $session->remaining_amount;
        $this->amount = $this->remaining;
    }

    public function updatedAmount()
    {
        if ($this->amount > $this->remaining) {
            $this->amount = $this->remaining;
        }
        if ($this->amount < 0) {
            $this->amount = 0;
        }
    }

    public function processPayment()
    {
        $this->validate();

        if ($this->amount <= 0) {
            $this->addError('amount', 'Amount must be greater than 0');
            return;
        }

        DB::beginTransaction();

        try {
            // Update session payment
            $newPaidAmount = $this->session->paid_amount + $this->amount;
            $this->session->paid_amount = $newPaidAmount;
            
            if ($newPaidAmount >= $this->session->session_amount) {
                $this->session->payment_status = 'paid';
                $this->session->paid_at = now();
            } else {
                $this->session->payment_status = 'partial';
            }
            
            $this->session->save();

            // Create payment record
            CuppingPayment::create([
                'cupping_session_id' => $this->session->id,
                'cupping_therapy_id' => $this->session->cupping_therapy_id,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'received_by' => Auth::id(),
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Remove from payment queue if fully paid
            if ($this->session->payment_status === 'paid') {
                CuppingQueue::where('cupping_session_id', $this->session->id)
                    ->where('queue_type', 'payment')
                    ->delete();
                
                // Add to treatment queue
                $lastPosition = CuppingQueue::where('queue_type', 'treatment')
                    ->where('status', 'waiting')
                    ->max('position') ?? 0;
                    
                CuppingQueue::create([
                    'cupping_session_id' => $this->session->id,
                    'queue_type' => 'treatment',
                    'position' => (int) $lastPosition + 1,
                    'status' => 'waiting'
                ]);
                
                // Update session treatment status
                $this->session->treatment_status = 'in_queue';
                $this->session->save();
            }

            DB::commit();

            $message = $this->session->payment_status === 'paid' 
                ? "✓ Payment completed! Session has been moved to treatment queue." 
                : "✓ Partial payment of " . number_format($this->amount, 2) . " ETB recorded.";

            $this->showAlertMessage($message, 'success');
            
            // Emit event to refresh parent component
            $this->dispatch('payment-processed');
            $this->dispatch('close-form-delayed');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Error: ' . $e->getMessage(), 'error');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.cashier.cupping-payment-form');
    }
}