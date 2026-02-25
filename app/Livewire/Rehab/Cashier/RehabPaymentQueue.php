<?php

namespace App\Livewire\Rehab\Cashier;

use App\Models\RehabOrder;
use App\Models\RehabEncounter;
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
            'encounter.encounter.doctor'
        ])->find($orderId);

        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedOrder = null;
    }

    public function openPaymentModal($orderId)
    {
        $this->paymentOrder = RehabOrder::with([
            'encounter.encounter.patient',
            'packages'
        ])->find($orderId);

        $this->paymentAmount = $this->paymentOrder->total_amount;
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
        $this->paymentMethod = 'cash';
        $this->paymentAmount = 0;
        $this->changeAmount = 0;
    }

    public function updatedPaymentAmount()
    {
        if ($this->paymentOrder) {
            if ($this->paymentAmount >= $this->paymentOrder->total_amount) {
                $this->changeAmount = $this->paymentAmount - $this->paymentOrder->total_amount;
            } else {
                $this->changeAmount = 0;
            }
        }
    }

    // public function processPayment()
    // {
    //     if (!$this->paymentOrder) {
    //         return;
    //     }

    //     $this->validate([
    //         'paymentMethod' => 'required|in:cash,card,insurance',
    //         'paymentAmount' => 'required|numeric|min:' . $this->paymentOrder->total_amount,
    //     ]);

    //     try {
    //         DB::transaction(function () {
    //             // Update order status with payment details
    //             $this->paymentOrder->update([
    //                 'status' => 'paid',
    //                 'payment_method' => $this->paymentMethod,
    //                 'paid_at' => now()
    //             ]);

    //             // Update rehab encounter status
    //             $this->paymentOrder->encounter->update([
    //                 'status' => 'submitted_to_doctor'
    //             ]);
    //         });

    //         $orderId = $this->paymentOrder->id;
    //         $this->closePaymentModal();
    //         $this->showAlertMessage('Payment processed successfully! Order #' . $orderId . ' marked as paid.', 'success');
    //         $this->dispatch('refreshQueue');

    //     } catch (\Exception $e) {
    //         $this->showAlertMessage('Error processing payment: ' . $e->getMessage(), 'error');
    //     }
    // }


    public function processPayment()
    {
        if (!$this->paymentOrder) {
            return;
        }

        $this->validate([
            'paymentMethod' => 'required|in:cash,card,insurance',
            'paymentAmount' => 'required|numeric|min:' . $this->paymentOrder->total_amount,
        ]);

        try {
            $hasBedItems = false; // Declare outside transaction

            DB::transaction(function () use (&$hasBedItems) { // Pass by reference
                // Update order status with payment details
                $this->paymentOrder->update([
                    'status' => 'paid',
                    'payment_method' => $this->paymentMethod,
                    'paid_at' => now()
                ]);

                // Check if order contains bed items
                $hasBedItems = $this->paymentOrder->packages
                    ->flatMap(function ($package) {
                        return $package->items;
                    })
                    ->where('item_type', 'bed')
                    ->isNotEmpty();

                // Update rehab encounter status based on bed items
                $newStatus = $hasBedItems ? 'waiting_bed_selection' : 'treatment_in_progress';

                $this->paymentOrder->encounter->update([
                    'status' => $newStatus
                ]);
            });

            $orderId = $this->paymentOrder->id;
            $this->closePaymentModal();

            // Use $hasBedItems here - now it's accessible
            if ($hasBedItems) {
                $this->showAlertMessage('Payment processed successfully! Order #' . $orderId . ' marked as paid. Patient moved to bed queue.', 'success');
                return redirect()->route('rehab.bed.queue');
            } else {
                $this->showAlertMessage('Payment processed successfully! Order #' . $orderId . ' marked as paid. Patient moved to treatment queue.', 'success');
                return redirect()->route('rehab.treatment.queue');
            }
        } catch (\Exception $e) {
            $this->showAlertMessage('Error processing payment: ' . $e->getMessage(), 'error');
        }
    }
    public function recheckPayment($orderId)
    {
        $order = RehabOrder::with('encounter.encounter.patient')->find($orderId);

        if ($order && $order->status === 'paid') {
            $paidDate = $order->paid_at ? $order->paid_at->format('M d, Y H:i') : 'Unknown date';
            $this->showAlertMessage('Payment rechecked and verified for Order #' . $orderId . ' - Paid on ' . $paidDate, 'info');
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
    // Add this method to calculate total with bed
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
            'packages'
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

        return view('livewire.rehab.cashier.rehab-payment-queue', [
            'orders' => $orders
        ]);
    }
}
