<?php

namespace App\Livewire\Pharmacy\Walkin;

use Livewire\Component;
use App\Models\WalkinOrder;
use App\Models\WalkinOrderItem;
use App\Models\PharmacyBatch;
use Illuminate\Support\Facades\DB;

class PharmacistDispense extends Component
{
    public $paidOrders = [];
    public $searchOrder = '';
    public $selectedOrder = null;
    public $showDispenseModal = false;
    public $dispensing = false;
    public $dispenseNotes = '';

    // Toast properties (matching your pattern)
    public $showToast = false;
    public $toastType = 'success';
    public $toastMessage = '';
    public $toastDetails = '';

    public function mount()
    {
        $this->loadPaidOrders();
    }

    public function loadPaidOrders()
    {
        $this->paidOrders = WalkinOrder::with(['items.medicine', 'items.batch', 'cashier'])
            ->where('status', 'paid')
            ->orderBy('paid_at', 'asc')
            ->when($this->searchOrder, function($query) {
                $query->where('order_number', 'like', '%' . $this->searchOrder . '%')
                      ->orWhere('customer_name', 'like', '%' . $this->searchOrder . '%')
                      ->orWhere('customer_phone', 'like', '%' . $this->searchOrder . '%');
            })
            ->get();
    }

    public function updatedSearchOrder()
    {
        $this->loadPaidOrders();
    }

    public function viewOrder($orderId)
    {
        $this->selectedOrder = WalkinOrder::with(['items.medicine', 'items.batch', 'cashier', 'items'])
            ->find($orderId);
        $this->showDispenseModal = true;
        $this->dispenseNotes = '';
    }

    public function closeModal()
    {
        $this->showDispenseModal = false;
        $this->selectedOrder = null;
        $this->dispensing = false;
        $this->dispenseNotes = '';
    }

    public function completeDispensing()
    {
        if (!$this->selectedOrder) {
            $this->showToast('error', 'No order selected', 'Please select an order to dispense.');
            return;
        }

        $this->dispensing = true;

        DB::beginTransaction();

        try {
            // Reload order to get fresh data
            $order = WalkinOrder::with(['items.medicine', 'items.batch'])->find($this->selectedOrder->id);

            // Check if order is still in 'paid' status
            if ($order->status !== 'paid') {
                throw new \Exception('Order is no longer in paid status. Current status: ' . $order->status);
            }

            // STEP 1: First check if all items have sufficient stock BEFORE dispensing
            foreach ($order->items as $item) {
                if ($item->status === 'dispensed') {
                    continue; // Skip already dispensed items
                }

                // Check stock availability
                $batch = PharmacyBatch::find($item->batch_id);
                if (!$batch) {
                    throw new \Exception("Batch not found for {$item->medicine->name}");
                }

                if ($batch->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for {$item->medicine->name}. Available: {$batch->quantity}, Required: {$item->quantity}");
                }
            }

            // STEP 2: Deduct stock from batches (like your approveOrder method)
            $this->deductStock($order);

            // STEP 3: Update order items to dispensed
            foreach ($order->items as $item) {
                if ($item->status !== 'dispensed') {
                    $item->update(['status' => 'dispensed']);
                }
            }

            // STEP 4: Update order status to dispensed
            $order->update([
                'status' => 'dispensed',
                'dispensed_by' => auth()->id(),
                'dispensed_at' => now(),
                'notes' => $this->dispenseNotes ?: $order->notes
            ]);

            DB::commit();

            $this->showToast(
                'success',
                'Order Dispensed',
                "Order #{$order->order_number} has been dispensed successfully."
            );

            // Close modal and refresh data
            $this->closeModal();
            $this->loadPaidOrders();

            // Dispatch event to refresh any other components
            $this->dispatch('order-dispensed');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error dispensing walk-in order: ' . $e->getMessage(), [
                'order_id' => $this->selectedOrder->id ?? null,
                'error' => $e->getMessage()
            ]);

            $this->showToast('error', 'Dispensing Failed', $e->getMessage());
            $this->dispensing = false;
        }
    }

    /**
     * Deduct stock from batches (matching your deductStock method from PharmacyDashboardComponent)
     */
    private function deductStock($order)
    {
        foreach ($order->items as $item) {
            if ($item->status === 'dispensed') {
                continue;
            }

            $required = $item->quantity;
            $batch = PharmacyBatch::find($item->batch_id);

            if (!$batch) {
                throw new \Exception("Batch not found for item ID: {$item->id}");
            }

            if ($batch->quantity < $required) {
                throw new \Exception("Insufficient stock in batch {$batch->batch_number}. Available: {$batch->quantity}, Required: {$required}");
            }

            // Deduct from batch
            $batch->decrement('quantity', $required);

            // Record stock transaction (matching your pattern)
            try {
                if ($this->tableExists('pharmacy_stock_transactions')) {
                    DB::table('pharmacy_stock_transactions')->insert([
                        'pharmacy_item_id' => $item->medicine_id,
                        'batch_id' => $batch->id,
                        'transaction_type' => 'walkin_sale',
                        'quantity' => -$required,
                        'unit_price' => $item->unit_price,
                        'total_price' => -$item->total_price,
                        'reference_type' => 'WalkinOrder',
                        'reference_id' => $order->id,
                        'notes' => "Walk-in sale - Order: {$order->order_number} - Customer: {$order->customer_name}" . ($this->dispenseNotes ? " - Notes: {$this->dispenseNotes}" : ""),
                        'created_by' => auth()->id(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to record stock transaction: ' . $e->getMessage());
                // Don't throw here - the stock is already deducted, just log the warning
            }
        }
    }

    public function showToast($type, $message, $details = '')
    {
        $this->showToast = true;
        $this->toastType = $type;
        $this->toastMessage = $message;
        $this->toastDetails = $details;

        // Auto hide toast after 5 seconds
        $this->dispatch('hide-toast');
    }

    public function hideToast()
    {
        $this->showToast = false;
        $this->toastMessage = '';
        $this->toastDetails = '';
    }

    /**
     * Check if a database table exists
     */
    private function tableExists($tableName)
    {
        try {
            return \Schema::hasTable($tableName);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.pharmacy.walkin.pharmacist-dispense');
    }
}
