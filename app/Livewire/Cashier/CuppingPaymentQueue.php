<?php

namespace App\Livewire\Cashier;

use App\Models\CuppingQueue;
use App\Models\CuppingSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CuppingPaymentQueue extends Component
{
    public $queueItems = [];
    public $selectedSession = null;
    public $showPaymentForm = false;

    public function mount()
    {
        $this->loadQueue();
    }

    public function loadQueue()
    {
        $this->queueItems = CuppingQueue::with(['cuppingSession.cuppingTherapy.encounter.patient'])
            ->where('queue_type', 'payment')
            ->where('status', 'waiting')
            ->orderBy('position')
            ->get();
    }

    public function processPayment($sessionId)
    {
        $this->selectedSession = CuppingSession::with(['cuppingTherapy', 'items.cuppingType', 'items.cuppingLocation'])
            ->find($sessionId);
        $this->showPaymentForm = true;
    }

    public function closePaymentForm()
    {
        $this->showPaymentForm = false;
        $this->selectedSession = null;
        $this->loadQueue();
    }
    public function render()
    {
        return view('livewire.cashier.cupping-payment-queue', [
            'queue' => $this->queueItems,
        ]);
    }
}
