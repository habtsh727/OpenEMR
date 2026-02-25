<?php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabEncounter;
use App\Models\RehabBedSelection;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class RehabPaymentQueue extends Component
{
    use WithPagination;

    public $search = '';
    public $tab = 'pending'; // pending, processed
    public $selectedOrder = null;
    public $showDetailsModal = false;

    // Payment properties
    public $showPaymentModal = false;
    public $paymentOrder = null;
    public $paymentMethod = 'cash';
    public $paymentAmount = 0;
    public $changeAmount = 0;

    // Bed selection properties
    public $bedSelection = null;
    public $bedCost = 0;
    public $packageTotal = 0;
    public $grandTotal = 0;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $listeners = ['refreshQueue' => '$refresh'];

    public function viewOrder($orderId)
    {
        $this->selectedOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages.items',
            'encounter.encounter.doctor',
            'bedSelections' => function ($query) {
                $query->with(['bedClass', 'bed'])->latest();
            }
        ])->find($orderId);

        // Calculate bed cost if exists
        if ($this->selectedOrder->bedSelections->isNotEmpty()) {
            $this->bedSelection = $this->selectedOrder->bedSelections->first();
            $this->bedCost = $this->bedSelection->total_price ?? 0;
        }

        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrder = null;
        $this->bedSelection = null;
        $this->bedCost = 0;
    }

    public function openPaymentModal($orderId)
    {
        $this->paymentOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages',
            'bedSelections' => function ($query) {
                $query->with(['bedClass', 'bed'])->latest();
            }
        ])->find($orderId);

        // Calculate package total
        $this->packageTotal = $this->paymentOrder->total_amount;
        
        // Get bed selection and cost
        $this->bedSelection = $this->paymentOrder->bedSelections->first();
        $this->bedCost = $this->bedSelection->total_price ?? 0;
        
        // Calculate grand total
        $this->grandTotal = $this->packageTotal + $this->bedCost;
        
        $this->paymentAmount = $this->grandTotal;
        $this->changeAmount = 0;
        $this->paymentMethod = 'cash';
        $this->showPaymentModal = true;

        if ($this->showDetailsModal) {
            $this->closeModal();
        }
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->paymentOrder = null;
        $this->bedSelection = null;
        $this->bedCost = 0;
        $this->packageTotal = 0;
        $this->grandTotal = 0;
        $this->paymentMethod = 'cash';
        $this->paymentAmount = 0;
        $this->changeAmount = 0;
    }

    public function updatedPaymentAmount()
    {
        if ($this->paymentOrder) {
            if ($this->paymentAmount >= $this->grandTotal) {
                $this->changeAmount = $this->paymentAmount - $this->grandTotal;
            } else {
                $this->changeAmount = 0;
            }
        }
    }

    public function processPayment()
    {
        if (!$this->paymentOrder) {
            return;
        }

        $this->validate([
            'paymentMethod' => 'required|in:cash,card,insurance',
            'paymentAmount' => 'required|numeric|min:' . $this->grandTotal,
        ]);

        try {
            DB::transaction(function () {
                // Update order status with payment details
                $this->paymentOrder->update([
                    'status' => 'paid',
                    'payment_method' => $this->paymentMethod,
                    'paid_at' => now()
                ]);

                // If there's a bed selection, update bed status to occupied
                if ($this->bedSelection) {
                    $this->bedSelection->bed->update([
                        'status' => 'occupied'
                    ]);
                    
                    // Update bed selection status if needed
                    $this->bedSelection->update([
                        'status' => 'completed'
                    ]);
                }

                // Check if order contains bed items
                $hasBedItems = $this->paymentOrder->packages
                    ->flatMap(function ($package) {
                        return $package->items;
                    })
                    ->where('item_type', 'bed')
                    ->isNotEmpty();

                // Update rehab encounter status
                $newStatus = $hasBedItems ? 'treatment_in_progress' : 'treatment_in_progress';
                
                $this->paymentOrder->encounter->update([
                    'status' => $newStatus
                ]);
            });

            $orderId = $this->paymentOrder->id;
            $this->closePaymentModal();
            
            $this->showAlertMessage('Payment processed successfully! Order #' . $orderId . ' marked as paid.', 'success');
            $this->dispatch('refreshQueue');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error processing payment: ' . $e->getMessage(), 'error');
        }
    }

    public function recheckPayment($orderId)
    {
        $order = RehabOrder::with([
            'encounter.encounter.patient',
            'bedSelections.bedClass'
        ])->find($orderId);

        if ($order && $order->status === 'paid') {
            $paidDate = $order->paid_at ? $order->paid_at->format('M d, Y H:i') : 'Unknown date';
            
            $bedInfo = '';
            if ($order->bedSelections->isNotEmpty()) {
                $bedSelection = $order->bedSelections->first();
                $bedInfo = " - Bed: {$bedSelection->bedClass->name} ({$bedSelection->duration_days} days, ETB " . number_format($bedSelection->total_price, 2) . ")";
            }
            
            $this->showAlertMessage('Payment rechecked and verified for Order #' . $orderId . ' - Paid on ' . $paidDate . $bedInfo, 'info');
        } else {
            $this->showAlertMessage('Order #' . $orderId . ' is not in paid status.', 'warning');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;

        // Auto hide after 5 seconds
        $this->dispatch('hideAlert');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function getTotalWithBedProperty()
    {
        if (!$this->paymentOrder) {
            return 0;
        }

        $total = $this->paymentOrder->total_amount;

        // Add bed cost if exists
        $bedSelection = $this->paymentOrder->bedSelections()
            ->where('status', 'selected')
            ->first();

        if ($bedSelection) {
            $total += $bedSelection->total_price;
        }

        return $total;
    }

    public function render()
    {
        $query = RehabOrder::with([
            'encounter.encounter.patient',
            'encounter.encounter.doctor',
            'packages',
            'bedSelections' => function ($query) {
                $query->with(['bedClass'])->latest();
            }
        ])
            ->when($this->tab === 'pending', function ($q) {
                $q->where('status', 'sent_to_cashier');
            })
            ->when($this->tab === 'processed', function ($q) {
                $q->where('status', 'paid');
            })
            ->when($this->search, function ($q) {
                $q->whereHas('encounter.encounter.patient', function ($patient) {
                    $patient->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest();

        $orders = $query->paginate(10);

        // Calculate totals with bed cost for each order
        foreach ($orders as $order) {
            $order->bed_cost = 0;
            $order->bed_duration = 0;
            $order->bed_class_name = null;
            
            if ($order->bedSelections->isNotEmpty()) {
                $bedSelection = $order->bedSelections->first();
                $order->bed_cost = $bedSelection->total_price ?? 0;
                $order->bed_duration = $bedSelection->duration_days ?? 0;
                $order->bed_class_name = $bedSelection->bedClass->name ?? null;
            }
            
            $order->grand_total = $order->total_amount + $order->bed_cost;
        }

        return view('livewire.rehab.cashier.rehab-payment-queue', [
            'orders' => $orders
        ]);
    }
}