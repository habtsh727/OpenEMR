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

    protected $listeners = ['paymentProcessed' => '$refresh'];

    public function mount()
    {
        Gate::authorize('pay lab order');
    }

    public function processPayment($labOrderId)
    {
        $labOrder = LabOrder::findOrFail($labOrderId);
        
        $labOrder->update([
            'payment_status' => 'paid',
            'paid_by' => Auth::id(),
            'paid_at' => now(),
        ]);

        $this->dispatch('paymentProcessed');
        session()->flash('message', 'Payment processed successfully.');
    }

    public function render()
    {
        $labOrders = LabOrder::with(['order.encounter.patient', 'labTest'])
            ->where('payment_status', 'unpaid')
            ->when($this->search, function ($query) {
                $query->whereHas('labTest', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterPatient, function ($query) {
                $query->whereHas('order.encounter.patient', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->filterPatient . '%')
                      ->orWhere('last_name', 'like', '%' . $this->filterPatient . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.order-lab.cashier-lab-payment', [
            'labOrders' => $labOrders,
        ]);
    }
}
