<?php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabEncounter;
use Livewire\Component;
use Livewire\WithPagination;

class RehabPaymentQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'sent_to_cashier';

    public function render()
    {
        $orders = RehabOrder::with(['encounter.encounter.patient', 'packages'])
            ->where('status', $this->status)
            ->when($this->search, function ($query) {
                $query->whereHas('encounter.encounter.patient', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.rehab.cashier.rehab-payment-queue', [
            'orders' => $orders
        ]);
    }
}
