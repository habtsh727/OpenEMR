<?php

namespace App\Livewire\Pharmacy;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CustomMedication;
use App\Models\CustomMedicationStock;
use App\Models\CustomMedicationMovement;
use App\Models\PharmacyFrequency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CustomMedicationComponent extends Component
{
    use WithPagination;
    
    public $search = '';
    public $status = 'active';
    public $perPage = 20;
    
    // Form properties
    public $showForm = false;
    public $editingId = null;
    public $formTitle = 'Add Custom Medication';
    
    // Medication properties
    public $name = '';
    public $ingredients = '';
    public $preparation_instructions = '';
    public $dosage = '';
    public $frequency_id = '';
    public $duration = '';
    public $instructions = '';
    public $base_price = 0;
    public $is_active = true;
    
    // Stats
    public $stats = [];
    
    // STOCK MANAGEMENT PROPERTIES
    public $showStockModal = false;
    public $showMovementModal = false;
    public $stockMedicationId = null;
    public $stockMedicationName = '';
    public $stockActionType = 'stock_in';
    public $stockQuantity = null;
    public $stockNote = '';
    public $currentStock = 0;
    public $lowStockAlert = 0;
    
    // MOVEMENT HISTORY
    public $movements = [];
    public $movementMedicationName = '';
    
    protected $listeners = [
        'refresh' => '$refresh',
        'medication-updated' => '$refresh',
        'confirm-delete' => 'deleteMedication',
        'close-stock-modal' => 'closeStockModal',
        'close-movement-modal' => 'closeMovementModal'
    ];
    
    public function mount()
    {
        $this->loadStats();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function loadStats()
    {
        $this->stats = [
            'total' => CustomMedication::count(),
            'active' => CustomMedication::where('is_active', true)->count(),
            'inactive' => CustomMedication::where('is_active', false)->count(),
            'recent' => CustomMedication::whereDate('created_at', today())->count(),
            'low_stock' => CustomMedicationStock::whereColumn('quantity', '<=', 'low_stock_alert')->count(),
            'out_of_stock' => CustomMedicationStock::where('quantity', '<=', 0)->count(),
        ];
    }
    
    public function showAddForm()
    {
        $this->resetForm();
        $this->formTitle = 'Add Custom Medication';
        $this->showForm = true;
    }
    
    public function showEditForm($id)
    {
        $medication = CustomMedication::with('stock')->findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $medication->name;
        $this->ingredients = $medication->ingredients;
        $this->preparation_instructions = $medication->preparation_instructions;
        $this->dosage = $medication->dosage;
        $this->frequency_id = $medication->frequency_id;
        $this->duration = $medication->duration;
        $this->instructions = $medication->instructions;
        $this->base_price = $medication->base_price;
        $this->is_active = $medication->is_active;
        
        $this->formTitle = 'Edit Custom Medication';
        $this->showForm = true;
    }
    
    public function saveMedication()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:custom_medications,name' . ($this->editingId ? ',' . $this->editingId : ''),
            'ingredients' => 'required|string|min:10',
            'preparation_instructions' => 'nullable|string|max:1000',
            'dosage' => 'required|string|max:100',
            'frequency_id' => 'required|exists:pharmacy_frequencies,id',
            'duration' => 'required|string|max:50',
            'instructions' => 'nullable|string|max:500',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ];
        
        $this->validate($rules);
        
        DB::beginTransaction();
        
        try {
            if ($this->editingId) {
                $medication = CustomMedication::findOrFail($this->editingId);
                $medication->update([
                    'name' => $this->name,
                    'ingredients' => $this->ingredients,
                    'preparation_instructions' => $this->preparation_instructions,
                    'dosage' => $this->dosage,
                    'frequency_id' => $this->frequency_id,
                    'duration' => $this->duration,
                    'instructions' => $this->instructions,
                    'base_price' => $this->base_price,
                    'is_active' => $this->is_active,
                    'created_by' => Auth::id()
                ]);
                $message = 'Custom medication updated successfully!';
            } else {
                $medication = CustomMedication::create([
                    'name' => $this->name,
                    'ingredients' => $this->ingredients,
                    'preparation_instructions' => $this->preparation_instructions,
                    'dosage' => $this->dosage,
                    'frequency_id' => $this->frequency_id,
                    'duration' => $this->duration,
                    'instructions' => $this->instructions,
                    'base_price' => $this->base_price,
                    'is_active' => $this->is_active,
                    'created_by' => Auth::id()
                ]);
                
                CustomMedicationStock::create([
                    'custom_medication_id' => $medication->id,
                    'quantity' => 0,
                    'low_stock_alert' => 0
                ]);
                
                $message = 'Custom medication created successfully!';
            }
            
            DB::commit();
            
            $this->resetForm();
            $this->showForm = false;
            $this->loadStats();
            
            session()->flash('success', $message);
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save medication: ' . $e->getMessage());
        }
    }
    
    public function openStockModal($medicationId, $action)
    {
        $medication = CustomMedication::findOrFail($medicationId);
        $stock = CustomMedicationStock::where('custom_medication_id', $medicationId)->first();
        
        $this->stockMedicationId = $medication->id;
        $this->stockMedicationName = $medication->name;
        $this->stockActionType = $action;
        $this->stockQuantity = null;
        $this->stockNote = '';
        $this->currentStock = $stock ? $stock->quantity : 0;
        $this->lowStockAlert = $stock ? $stock->low_stock_alert : 0;
        
        $this->showStockModal = true;
    }
    
    public function closeStockModal()
    {
        $this->reset(['showStockModal', 'stockMedicationId', 'stockMedicationName', 
                      'stockActionType', 'stockQuantity', 'stockNote', 'currentStock', 'lowStockAlert']);
    }
    
    // FIXED: ADD STOCK - Adds to current stock
    public function addStock()
    {
        $this->validate([
            'stockQuantity' => 'required|numeric|min:0.01',
            'stockNote' => 'nullable|string|max:500'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Get the stock record
            $stock = CustomMedicationStock::where('custom_medication_id', $this->stockMedicationId)->first();
            
            if (!$stock) {
                // Create if doesn't exist
                $stock = CustomMedicationStock::create([
                    'custom_medication_id' => $this->stockMedicationId,
                    'quantity' => 0,
                    'low_stock_alert' => 0
                ]);
            }
            
            $oldQuantity = $stock->quantity;
            $addQuantity = $this->stockQuantity;
            $newQuantity = $oldQuantity + $addQuantity; // THIS IS KEY - ADDING to current
            
            // Update stock
            $stock->quantity = $newQuantity;
            $stock->save();
            
            // Create movement
            CustomMedicationMovement::create([
                'custom_medication_id' => $this->stockMedicationId,
                'type' => 'stock_in',
                'quantity' => $addQuantity,
                'note' => $this->stockNote,
                'performed_by' => Auth::id(),
            ]);
            
            DB::commit();
            
            $this->closeStockModal();
            $this->loadStats();
            
            session()->flash('success', "Stock ADDED successfully! {$oldQuantity} + {$addQuantity} = {$newQuantity}");
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }
    
    // FIXED: REDUCE STOCK - Subtracts from current stock
    public function reduceStock()
    {
        $this->validate([
            'stockQuantity' => 'required|numeric|min:0.01',
            'stockNote' => 'nullable|string|max:500'
        ]);
        
        $stock = CustomMedicationStock::where('custom_medication_id', $this->stockMedicationId)->first();
        
        if (!$stock) {
            $this->addError('stockQuantity', 'No stock record found for this medication.');
            return;
        }
        
        $currentQty = $stock->quantity;
        $reduceQty = $this->stockQuantity;
        
        if ($currentQty < $reduceQty) {
            $this->addError('stockQuantity', 'Insufficient stock. Current: ' . $currentQty);
            return;
        }
        
        DB::beginTransaction();
        
        try {
            $newQuantity = $currentQty - $reduceQty; // THIS IS KEY - SUBTRACTING from current
            
            // Update stock
            $stock->quantity = $newQuantity;
            $stock->save();
            
            // Create movement
            CustomMedicationMovement::create([
                'custom_medication_id' => $this->stockMedicationId,
                'type' => 'stock_out',
                'quantity' => -$reduceQty,
                'note' => $this->stockNote,
                'performed_by' => Auth::id(),
            ]);
            
            DB::commit();
            
            $this->closeStockModal();
            $this->loadStats();
            
            session()->flash('success', "Stock REDUCED successfully! {$currentQty} - {$reduceQty} = {$newQuantity}");
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to reduce stock: ' . $e->getMessage());
        }
    }
    
    // FIXED: ADJUST STOCK - Sets to exact value
    public function adjustStock()
    {
        $this->validate([
            'stockQuantity' => 'required|numeric|min:0',
            'stockNote' => 'nullable|string|max:500'
        ]);
        
        DB::beginTransaction();
        
        try {
            $stock = CustomMedicationStock::where('custom_medication_id', $this->stockMedicationId)->first();
            
            if (!$stock) {
                $stock = CustomMedicationStock::create([
                    'custom_medication_id' => $this->stockMedicationId,
                    'quantity' => 0,
                    'low_stock_alert' => 0
                ]);
            }
            
            $oldQuantity = $stock->quantity;
            $newQuantity = $this->stockQuantity;
            $quantityDiff = $newQuantity - $oldQuantity;
            
            // Set to exact new quantity
            $stock->quantity = $newQuantity;
            $stock->save();
            
            // Create movement
            CustomMedicationMovement::create([
                'custom_medication_id' => $this->stockMedicationId,
                'type' => 'adjustment',
                'quantity' => $quantityDiff,
                'note' => $this->stockNote ?: "Adjusted from {$oldQuantity} to {$newQuantity}",
                'performed_by' => Auth::id(),
            ]);
            
            DB::commit();
            
            $this->closeStockModal();
            $this->loadStats();
            
            session()->flash('success', "Stock ADJUSTED successfully! {$oldQuantity} → {$newQuantity}");
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }
    
    public function showMovementHistory($medicationId)
    {
        $medication = CustomMedication::findOrFail($medicationId);
        
        $this->movements = CustomMedicationMovement::where('custom_medication_id', $medicationId)
            ->with('performer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($movement) {
                return [
                    'id' => $movement->id,
                    'type' => $movement->type,
                    'type_label' => ucwords(str_replace('_', ' ', $movement->type)),
                    'quantity' => $movement->quantity,
                    'quantity_formatted' => $movement->quantity > 0 ? '+' . $movement->quantity : $movement->quantity,
                    'note' => $movement->note,
                    'performed_by' => $movement->performer?->name ?? 'System',
                    'created_at' => $movement->created_at->format('M d, Y H:i'),
                    'created_at_diff' => $movement->created_at->diffForHumans()
                ];
            });
        
        $this->movementMedicationName = $medication->name;
        $this->showMovementModal = true;
    }
    
    public function closeMovementModal()
    {
        $this->reset(['showMovementModal', 'movements', 'movementMedicationName']);
    }
    
    public function toggleStatus($id)
    {
        $medication = CustomMedication::findOrFail($id);
        $medication->update(['is_active' => !$medication->is_active]);
        
        $status = $medication->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Medication {$status} successfully!");
        $this->loadStats();
        $this->dispatch('medication-updated');
    }
    
    public function confirmDelete($id)
    {
        $medication = CustomMedication::findOrFail($id);
        
        if ($medication->orderItems()->count() > 0) {
            session()->flash('error', 'Cannot delete medication that has been used in orders. You can deactivate it instead.');
            return;
        }
        
        $this->dispatch('confirm-delete-modal', 
            title: 'Delete Medication',
            message: "Are you sure you want to delete '{$medication->name}'? This action cannot be undone.",
            confirmText: 'Delete',
            cancelText: 'Cancel',
            id: $id
        );
    }
    
    public function deleteMedication($id)
    {
        try {
            $medication = CustomMedication::findOrFail($id);
            $medication->delete();
            
            session()->flash('success', 'Medication deleted successfully!');
            $this->loadStats();
            $this->dispatch('medication-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete medication: ' . $e->getMessage());
        }
    }
    
    public function duplicateMedication($id)
    {
        $original = CustomMedication::findOrFail($id);
        
        $this->editingId = null;
        $this->name = $original->name . ' (Copy)';
        $this->ingredients = $original->ingredients;
        $this->preparation_instructions = $original->preparation_instructions;
        $this->dosage = $original->dosage;
        $this->frequency_id = $original->frequency_id;
        $this->duration = $original->duration;
        $this->instructions = $original->instructions;
        $this->base_price = $original->base_price;
        $this->is_active = true;
        
        $this->formTitle = 'Duplicate Medication';
        $this->showForm = true;
    }
    public function testStockNow($medicationId)
{
    try {
        // Get current stock
        $stock = CustomMedicationStock::where('custom_medication_id', $medicationId)->first();
        
        if (!$stock) {
            session()->flash('error', 'No stock record found');
            return;
        }
        
        $oldQuantity = $stock->quantity;
        
        // Force update to oldQuantity + 5
        $stock->quantity = $oldQuantity + 5;
        $stock->save();
        
        // Verify
        $newStock = CustomMedicationStock::where('custom_medication_id', $medicationId)->first();
        
        session()->flash('info', 
            'BEFORE: ' . $oldQuantity . '<br>' .
            'AFTER: ' . $newStock->quantity . '<br>' .
            'CHANGE: +5'
        );
        
        $this->dispatch('medication-updated');
        
    } catch (\Exception $e) {
        session()->flash('error', 'Test failed: ' . $e->getMessage());
    }
}
    public function resetForm()
    {
        $this->reset([
            'editingId',
            'name',
            'ingredients',
            'preparation_instructions',
            'dosage',
            'frequency_id',
            'duration',
            'instructions',
            'base_price',
            'is_active'
        ]);
        $this->is_active = true;
    }
    
    public function isLowStock($stock)
    {
        if (!$stock) return false;
        return $stock->quantity <= $stock->low_stock_alert && $stock->low_stock_alert > 0;
    }
    
    public function render()
    {
        $medications = CustomMedication::with(['frequency', 'creator', 'stock'])
            ->when($this->status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('ingredients', 'like', '%' . $this->search . '%')
                      ->orWhere('dosage', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);
        
        $frequencies = PharmacyFrequency::orderBy('name')->get();
        
        return view('livewire.pharmacy.custom-medication-component', [
            'medications' => $medications,
            'frequencies' => $frequencies
        ]);
    }
}