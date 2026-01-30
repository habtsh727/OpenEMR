<?php

namespace App\Livewire\Pharmacy;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicationOrder;
use App\Models\MedicationDispensation;
use App\Models\PharmacyBatch;

class PharmacyQueueComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = 'paid';
    public $selectedOrderId = null;
    public $showDispenseModal = false;
    public $showStockModal = false;
    
    // Dispensation properties
    public $dispensationNotes = '';
    public $stockItems = [];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function selectOrder($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->showDispenseModal = true;
        
        // Load order items to check stock
        $order = MedicationOrder::with(['items.drug.batches'])->find($orderId);
        
        $this->stockItems = $order->items->map(function ($item) {
            if ($item->drug) {
                $availableStock = $item->drug->batches()
                    ->where('quantity', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->sum('quantity');
                    
                return [
                    'item_id' => $item->id,
                    'name' => $item->drug->name,
                    'required' => $item->quantity,
                    'available' => $availableStock,
                    'has_stock' => $availableStock >= $item->quantity,
                    'type' => 'standard'
                ];
            } else {
                return [
                    'item_id' => $item->id,
                    'name' => $item->customMedication->name,
                    'required' => $item->quantity,
                    'available' => null, // Custom meds don't have stock
                    'has_stock' => true, // Always available
                    'type' => 'custom'
                ];
            }
        })->toArray();
    }
    
    public function approveOrder()
    {
        $order = MedicationOrder::findOrFail($this->selectedOrderId);
        
        // Check if all standard drugs have sufficient stock
        $insufficientStock = collect($this->stockItems)
            ->filter(fn($item) => $item['type'] === 'standard' && !$item['has_stock'])
            ->isNotEmpty();
            
        if ($insufficientStock) {
            $this->dispatch('error', message: 'Insufficient stock for some medications.');
            $this->showStockModal = true;
            return;
        }
        
        // Create dispensation record
        $dispensation = MedicationDispensation::create([
            'medication_order_id' => $order->id,
            'pharmacist_id' => auth()->id(),
            'status' => 'approved',
            'notes' => $this->dispensationNotes
        ]);
        
        // Update order status
        $order->update(['status' => 'approved']);
        
        $this->resetDispenseModal();
        $this->dispatch('order-approved', orderId: $order->id);
    }
    
    public function dispenseOrder()
    {
        $order = MedicationOrder::findOrFail($this->selectedOrderId);
        
        // Update stock for standard medications
        foreach ($order->items as $item) {
            if ($item->drug) {
                $this->deductStock($item->drug_id, $item->quantity);
            }
        }
        
        // Update dispensation record
        $dispensation = MedicationDispensation::where('medication_order_id', $order->id)->first();
        $dispensation->update([
            'status' => 'dispensed',
            'dispensed_at' => now(),
            'stock_updated' => true,
            'notes' => $this->dispensationNotes
        ]);
        
        // Update order status
        $order->update(['status' => 'dispensed']);
        
        $this->resetDispenseModal();
        $this->dispatch('order-dispensed', orderId: $order->id);
        session()->flash('success', 'Medications dispensed successfully!');
    }
    
    private function deductStock($drugId, $quantity)
    {
        $batches = PharmacyBatch::where('medicine_id', $drugId)
            ->where('quantity', '>', 0)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date') // FIFO - First expiry first out
            ->get();
            
        $remaining = $quantity;
        
        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            
            $deduct = min($remaining, $batch->quantity);
            $batch->decrement('quantity', $deduct);
            $remaining -= $deduct;
        }
    }
    
    public function resetDispenseModal()
    {
        $this->reset([
            'selectedOrderId', 'dispensationNotes', 'stockItems',
            'showDispenseModal', 'showStockModal'
        ]);
    }
    
    public function render()
    {
        $orders = MedicationOrder::with(['encounter.patient', 'doctor'])
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('encounter.patient', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $stats = [
            'total_paid' => MedicationOrder::where('status', 'paid')->count(),
            'total_approved' => MedicationOrder::where('status', 'approved')->count(),
            'total_dispensed' => MedicationOrder::where('status', 'dispensed')->count(),
        ];
        
        return view('livewire.doctor.pharmacy-queue-component', [
            'orders' => $orders,
            'stats' => $stats
        ]);
    }
}