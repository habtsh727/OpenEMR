<?php

namespace App\Livewire\OrderLab;

use App\Models\LabOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class CashierLabPayment extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPatient = '';
    public $processingId = null;
    public $showConfirmation = false;

    protected $listeners = ['paymentProcessed' => '$refresh'];

    public function mount()
    {
        $this->authorize('receive_payment');
    }

    public function confirmPayment($labOrderId)
    {
        $this->processingId = $labOrderId;
        $this->showConfirmation = true;
    }

    public function resetSelection()
    {
        $this->processingId = null;
        $this->showConfirmation = false;
    }

    public function processPayment()
    {
        if (!$this->processingId) {
            return;
        }

        $labOrder = LabOrder::findOrFail($this->processingId);
        
        $labOrder->update([
            'payment_status' => 'paid',
            'paid_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        $this->resetSelection();
        $this->dispatch('paymentProcessed');
        session()->flash('success', 'Payment processed successfully for ' . $labOrder->labTest->name . '.');
    }

    public function render()
    {
        $labOrders = LabOrder::with(['order.encounter.patient', 'labTest'])
            ->where('payment_status', 'unpaid')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('labTest', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('order.encounter.patient', function ($q2) {
                        $q2->where('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%')
                          ->orWhere('card_number', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->filterPatient, function ($query) {
                $query->whereHas('order.encounter.patient', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->filterPatient . '%')
                      ->orWhere('last_name', 'like', '%' . $this->filterPatient . '%');
                });
            })
            ->orderByRaw("FIELD(priority, 'stat', 'urgent', 'routine')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.order-lab.cashier-lab-payment', [
            'labOrders' => $labOrders,
        ]);
    }
}