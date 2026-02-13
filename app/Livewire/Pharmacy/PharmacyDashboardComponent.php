<?php

namespace App\Livewire\Pharmacy;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MedicationOrder;
use App\Models\MedicationDispensation;
use App\Models\PharmacyItem;
use App\Models\PharmacyBatch;
use Illuminate\Support\Facades\DB;
use App\Models\CustomMedicationStock;
use App\Models\CustomMedicationMovement;
class PharmacyDashboardComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = 'pending'; // pending, approved, dispensed
    public $selectedOrderId = null;
    public $showOrderDetails = false;
    public $showDispenseModal = false;
    
    // Order details
    public $orderDetails = null;
    public $dispensation = null;
    public $stockStatus = [];
    
    // Dispense properties
    public $dispenseNotes = '';
    public $dispensedAt;
    
    // Approval properties
    public $approvalNotes = '';
    
    // Stats
    public $stats = [];
    
    // Toast properties
    public $showToast = false;
    public $toastType = 'success';
    public $toastMessage = '';
    public $toastDetails = '';
    
    protected $listeners = [
        'order-updated' => '$refresh',
        'stock-updated' => '$refresh'
    ];
    
    public function mount()
    {
        $this->loadStats();
        $this->dispensedAt = now()->format('Y-m-d\TH:i');
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    public function loadStats()
    {
        $this->stats = [
            'pending' => MedicationDispensation::where('status', 'pending')->count(),
            'approved' => MedicationDispensation::where('status', 'approved')->count(),
            'dispensed' => MedicationDispensation::where('status', 'dispensed')->count(),
            'total_orders' => MedicationDispensation::count(),
        ];
    }
    
    public function viewOrder($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->loadOrderDetails();
        $this->showOrderDetails = true;
    }
    
    public function loadOrderDetails()
    {
        $this->orderDetails = MedicationOrder::with([
            'items.drug',
            'items.customMedication',
            'items.frequency',
            'encounter.patient',
            'encounter.doctor',
            'payment',
            'dispensation' // This should be the relationship name
        ])->find($this->selectedOrderId);
        
        if (!$this->orderDetails) {
            $this->showToast('error', 'Order not found', 'The requested order does not exist.');
            $this->showOrderDetails = false;
            return;
        }
        
        // Load or create dispensation record
        $this->dispensation = $this->orderDetails->dispensation ?? 
            MedicationDispensation::firstOrCreate(
                ['medication_order_id' => $this->selectedOrderId],
                [
                    'status' => 'pending',
                    'pharmacist_id' => auth()->id()
                ]
            );
        
        // Check stock for each item
        $this->checkStockAvailability();
    }
    
    // public function checkStockAvailability()
    // {
    //     $this->stockStatus = [];
        
    //     if (!$this->orderDetails) return;
        
    //     foreach ($this->orderDetails->items as $item) {
    //         if ($item->drug_id) {
    //             // Standard medication - check stock
    //             $drug = PharmacyItem::with('batches')->find($item->drug_id);
    //             $availableStock = $drug ? $drug->batches()->sum('quantity') : 0;
    //             $sufficient = $availableStock >= $item->quantity;
                
    //             $this->stockStatus[$item->id] = [
    //                 'type' => 'standard',
    //                 'drug_name' => $drug->name ?? 'Unknown',
    //                 'required' => $item->quantity,
    //                 'available' => $availableStock,
    //                 'sufficient' => $sufficient,
    //                 'batches' => $drug ? $drug->batches : []
    //             ];
    //         } else {
    //             // Custom medication - no stock check needed
    //             $this->stockStatus[$item->id] = [
    //                 'type' => 'custom',
    //                 'drug_name' => $item->customMedication->name ?? 'Custom Medication',
    //                 'required' => $item->quantity,
    //                 'available' => null,
    //                 'sufficient' => true,
    //                 'batches' => []
    //             ];
    //         }
    //     }
    // }
    
    public function checkStockAvailability()
{
    $this->stockStatus = [];
    
    if (!$this->orderDetails) return;
    
    foreach ($this->orderDetails->items as $item) {
        if ($item->drug_id) {
            // Standard medication - check stock
            $drug = PharmacyItem::with('batches')->find($item->drug_id);
            $availableStock = $drug ? $drug->batches()->sum('quantity') : 0;
            $sufficient = $availableStock >= $item->quantity;
            
            $this->stockStatus[$item->id] = [
                'type' => 'standard',
                'drug_name' => $drug->name ?? 'Unknown',
                'required' => $item->quantity,
                'available' => $availableStock,
                'sufficient' => $sufficient,
                'batches' => $drug ? $drug->batches : []
            ];
        } elseif ($item->custom_medication_id) {
            // 🟢 FIXED: Custom medication - check stock from custom_medication_stocks
            $customStock = \App\Models\CustomMedicationStock::where('custom_medication_id', $item->custom_medication_id)->first();
            $availableStock = $customStock ? $customStock->quantity : 0;
            $sufficient = $availableStock >= $item->quantity;
            
            $this->stockStatus[$item->id] = [
                'type' => 'custom',
                'drug_name' => $item->customMedication->name ?? 'Custom Medication',
                'required' => $item->quantity,
                'available' => $availableStock,
                'sufficient' => $sufficient,
                'batches' => [] // Custom medications don't use batches
            ];
        }
    }
}
    public function approveOrder()
    {
        $this->validate([
            'approvalNotes' => 'nullable|string|max:500'
        ]);
        
        if (!$this->dispensation) {
            $this->showToast('error', 'Dispensation record not found', 'Cannot approve order.');
            return;
        }
        
        // Check if all items have sufficient stock
        $allSufficient = collect($this->stockStatus)->every(function ($status) {
            return $status['sufficient'];
        });
        
        if (!$allSufficient) {
            $this->showToast('error', 'Cannot approve order', 'Insufficient stock for some items.');
            return;
        }
        
        try {
            // Update dispensation status
            $this->dispensation->update([
                'status' => 'approved',
                'notes' => $this->approvalNotes,
                'pharmacist_id' => auth()->id()
            ]);
            
            // Deduct stock for standard medications
            $this->deductStock();
            
            $this->showToast('success', 'Order Approved', 'Order #' . $this->orderDetails->id . ' has been approved and stock deducted.');
            
            $this->reset(['approvalNotes', 'showOrderDetails', 'selectedOrderId']);
            $this->loadStats();
            $this->dispatch('order-updated');
            
        } catch (\Exception $e) {
            \Log::error('Error approving order: ' . $e->getMessage());
            $this->showToast('error', 'Approval Failed', 'Error: ' . $e->getMessage());
        }
    }
    
    // public function deductStock()
    // {
    //     foreach ($this->orderDetails->items as $item) {
    //         if ($item->drug_id) {
    //             $required = $item->quantity;
    //             $batches = PharmacyBatch::where('medicine_id', $item->drug_id)
    //                 ->where('quantity', '>', 0)
    //                 ->orderBy('expiry_date')
    //                 ->get();
                
    //             foreach ($batches as $batch) {
    //                 if ($required <= 0) break;
                    
    //                 $deduct = min($required, $batch->quantity);
    //                 $batch->decrement('quantity', $deduct);
    //                 $required -= $deduct;
                    
    //                 try {
    //                     // Record the deduction if table exists
    //                     if ($this->tableExists('pharmacy_stock_transactions')) {
    //                         DB::table('pharmacy_stock_transactions')->insert([
    //                             'pharmacy_item_id' => $item->drug_id,
    //                             'batch_id' => $batch->id,
    //                             'transaction_type' => 'dispense',
    //                             'quantity' => -$deduct,
    //                             'reference_type' => 'MedicationOrder',
    //                             'reference_id' => $this->selectedOrderId,
    //                             'notes' => 'Dispensed for Order #' . $this->selectedOrderId,
    //                             'created_by' => auth()->id(),
    //                             'created_at' => now(),
    //                             'updated_at' => now()
    //                         ]);
    //                     } else {
    //                         // Log that table doesn't exist but continue
    //                         \Log::warning('pharmacy_stock_transactions table does not exist. Stock updated without transaction record.');
    //                     }
    //                 } catch (\Exception $e) {
    //                     // Log error but continue processing
    //                     \Log::warning('Failed to record stock transaction: ' . $e->getMessage());
    //                 }
    //             }
                
    //             // Update dispensation stock_updated flag
    //             $this->dispensation->update(['stock_updated' => true]);
    //         }
    //     }
    // }
    
    public function deductStock()
{
    foreach ($this->orderDetails->items as $item) {
        if ($item->drug_id) {
            // Standard medication - deduct from PharmacyBatch
            $required = $item->quantity;
            $batches = PharmacyBatch::where('medicine_id', $item->drug_id)
                ->where('quantity', '>', 0)
                ->orderBy('expiry_date')
                ->get();
            
            foreach ($batches as $batch) {
                if ($required <= 0) break;
                
                $deduct = min($required, $batch->quantity);
                $batch->decrement('quantity', $deduct);
                $required -= $deduct;
                
                try {
                    if ($this->tableExists('pharmacy_stock_transactions')) {
                        DB::table('pharmacy_stock_transactions')->insert([
                            'pharmacy_item_id' => $item->drug_id,
                            'batch_id' => $batch->id,
                            'transaction_type' => 'dispense',
                            'quantity' => -$deduct,
                            'reference_type' => 'MedicationOrder',
                            'reference_id' => $this->selectedOrderId,
                            'notes' => 'Dispensed for Order #' . $this->selectedOrderId,
                            'created_by' => auth()->id(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to record stock transaction: ' . $e->getMessage());
                }
            }
        } elseif ($item->custom_medication_id) {
            // 🟢 FIXED: CUSTOM MEDICATION - Deduct from CustomMedicationStock
            $customStock = \App\Models\CustomMedicationStock::where('custom_medication_id', $item->custom_medication_id)->first();
            
            if (!$customStock) {
                \Log::warning('No stock record found for custom medication ID: ' . $item->custom_medication_id);
                continue;
            }
            
            $currentQty = floatval($customStock->quantity);
            $requiredQty = floatval($item->quantity);
            
            if ($currentQty < $requiredQty) {
                // Insufficient stock - you might want to handle this differently
                \Log::error('Insufficient custom medication stock. Required: ' . $requiredQty . ', Available: ' . $currentQty);
                throw new \Exception('Insufficient stock for custom medication: ' . ($item->customMedication->name ?? 'Unknown'));
            }
            
            $newQuantity = $currentQty - $requiredQty;
            
            // Update custom medication stock
            $customStock->quantity = $newQuantity;
            $customStock->save();
            
            // Create movement record for custom medication
            try {
                \App\Models\CustomMedicationMovement::create([
                    'custom_medication_id' => $item->custom_medication_id,
                    'type' => 'dispensed',
                    'quantity' => -$requiredQty,
                    'reference_type' => 'MedicationOrder',
                    'reference_id' => $this->selectedOrderId,
                    'performed_by' => auth()->id(),
                    'note' => 'Dispensed for Order #' . $this->selectedOrderId
                ]);
            } catch (\Exception $e) {
                \Log::warning('Failed to create custom medication movement: ' . $e->getMessage());
            }
            
            \Log::info('Custom medication stock updated', [
                'medication_id' => $item->custom_medication_id,
                'old' => $currentQty,
                'deducted' => $requiredQty,
                'new' => $newQuantity,
                'order_id' => $this->selectedOrderId
            ]);
        }
    }
    
    // Update dispensation stock_updated flag
    $this->dispensation->update(['stock_updated' => true]);
}
    public function startDispensing()
    {
        $this->showDispenseModal = true;
    }
    
    public function completeDispensing()
    {
        $this->validate([
            'dispenseNotes' => 'nullable|string|max:500',
            'dispensedAt' => 'required|date'
        ]);
        
        if (!$this->dispensation) {
            $this->showToast('error', 'Dispensation record not found', 'Cannot complete dispensing.');
            return;
        }
        
        try {
            $this->dispensation->update([
                'status' => 'dispensed',
                'dispensed_at' => $this->dispensedAt,
                'notes' => $this->dispenseNotes,
                'pharmacist_id' => auth()->id()
            ]);
            
            // Also update medication_order status
            $this->orderDetails->update([
                'status' => 'dispensed'
            ]);
            
            $this->showToast('success', 'Order Completed', 'Medications dispensed for Order #' . $this->orderDetails->id);
            
            $this->reset([
                'dispenseNotes', 'showDispenseModal',
                'showOrderDetails', 'selectedOrderId'
            ]);
            $this->loadStats();
            $this->dispatch('order-updated');
            
        } catch (\Exception $e) {
            \Log::error('Error completing dispensing: ' . $e->getMessage());
            $this->showToast('error', 'Dispensing Failed', 'Error: ' . $e->getMessage());
        }
    }
    
    public function cancelOrder()
    {
        if (!$this->dispensation) {
            $this->showToast('error', 'Dispensation record not found', 'Cannot cancel order.');
            return;
        }
        
        try {
            // Restore stock if it was updated
            if ($this->dispensation->stock_updated) {
                $this->restoreStock();
            }
            
            $this->dispensation->update([
                'status' => 'pending',
                'stock_updated' => false,
                'notes' => 'Cancelled by pharmacy: ' . ($this->dispensation->notes ?? '')
            ]);
            
            $this->showToast('info', 'Order Cancelled', 'Order #' . $this->orderDetails->id . ' has been cancelled.');
            
            $this->reset(['showOrderDetails', 'selectedOrderId']);
            $this->loadStats();
            $this->dispatch('order-updated');
            
        } catch (\Exception $e) {
            \Log::error('Error cancelling order: ' . $e->getMessage());
            $this->showToast('error', 'Cancellation Failed', 'Error: ' . $e->getMessage());
        }
    }
    
    // public function restoreStock()
    // {
    //     foreach ($this->orderDetails->items as $item) {
    //         if ($item->drug_id) {
    //             // Find the batch to restore (simplified - you might want to track which batch was used)
    //             $batch = PharmacyBatch::where('medicine_id', $item->drug_id)
    //                 ->orderBy('created_at', 'desc')
    //                 ->first();
                
    //             if ($batch) {
    //                 $batch->increment('quantity', $item->quantity);
                    
    //                 try {
    //                     // Record the restoration if table exists
    //                     if ($this->tableExists('pharmacy_stock_transactions')) {
    //                         DB::table('pharmacy_stock_transactions')->insert([
    //                             'pharmacy_item_id' => $item->drug_id,
    //                             'batch_id' => $batch->id,
    //                             'transaction_type' => 'return',
    //                             'quantity' => $item->quantity,
    //                             'reference_type' => 'MedicationOrder',
    //                             'reference_id' => $this->selectedOrderId,
    //                             'notes' => 'Stock restored for cancelled Order #' . $this->selectedOrderId,
    //                             'created_by' => auth()->id(),
    //                             'created_at' => now(),
    //                             'updated_at' => now()
    //                         ]);
    //                     }
    //                 } catch (\Exception $e) {
    //                     // Log error but continue processing
    //                     \Log::warning('Failed to record stock restoration: ' . $e->getMessage());
    //                 }
    //             }
    //         }
    //     }
    // }
    
   public function restoreStock()
{
    foreach ($this->orderDetails->items as $item) {
        if ($item->drug_id) {
            // Standard medication restore (your existing code)
            $batch = PharmacyBatch::where('medicine_id', $item->drug_id)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($batch) {
                $batch->increment('quantity', $item->quantity);
                
                try {
                    if ($this->tableExists('pharmacy_stock_transactions')) {
                        DB::table('pharmacy_stock_transactions')->insert([
                            'pharmacy_item_id' => $item->drug_id,
                            'batch_id' => $batch->id,
                            'transaction_type' => 'return',
                            'quantity' => $item->quantity,
                            'reference_type' => 'MedicationOrder',
                            'reference_id' => $this->selectedOrderId,
                            'notes' => 'Stock restored for cancelled Order #' . $this->selectedOrderId,
                            'created_by' => auth()->id(),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to record stock restoration: ' . $e->getMessage());
                }
            }
        } elseif ($item->custom_medication_id) {
            // 🟢 FIXED: Restore custom medication stock
            $customStock = \App\Models\CustomMedicationStock::where('custom_medication_id', $item->custom_medication_id)->first();
            
            if ($customStock) {
                $oldQty = $customStock->quantity;
                $customStock->quantity = $oldQty + $item->quantity;
                $customStock->save();
                
                // Create movement record for restoration
                try {
                    \App\Models\CustomMedicationMovement::create([
                        'custom_medication_id' => $item->custom_medication_id,
                        'type' => 'stock_in',
                        'quantity' => $item->quantity,
                        'reference_type' => 'MedicationOrder',
                        'reference_id' => $this->selectedOrderId,
                        'performed_by' => auth()->id(),
                        'note' => 'Stock restored for cancelled Order #' . $this->selectedOrderId
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Failed to create custom medication movement: ' . $e->getMessage());
                }
                
                \Log::info('Custom medication stock restored', [
                    'medication_id' => $item->custom_medication_id,
                    'old' => $oldQty,
                    'restored' => $item->quantity,
                    'new' => $customStock->quantity,
                    'order_id' => $this->selectedOrderId
                ]);
            }
        }
    }
}
    public function printOrderLabel()
    {
        $this->dispatch('print-order-label', orderId: $this->selectedOrderId);
    }
    
    public function showToast($type, $message, $details = '')
    {
        $this->showToast = true;
        $this->toastType = $type;
        $this->toastMessage = $message;
        $this->toastDetails = $details;
        
        // Auto hide toast after 5 seconds
        $this->dispatch('start-toast-timer');
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
        // Get orders with their dispensations
        $dispensations = MedicationDispensation::with(['order.encounter.patient', 'order.payment'])
            ->when($this->status === 'pending', function ($query) {
                $query->where('status', 'pending');
            })
            ->when($this->status === 'approved', function ($query) {
                $query->where('status', 'approved');
            })
            ->when($this->status === 'dispensed', function ($query) {
                $query->where('status', 'dispensed');
            })
            ->when($this->search, function ($query) {
                $query->whereHas('order.encounter.patient', function ($patientQuery) {
                    $patientQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('id', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('order', function ($orderQuery) {
                    $orderQuery->where('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('livewire.pharmacy.pharmacy-dashboard-component', [
            'dispensations' => $dispensations
        ]);
    }
}