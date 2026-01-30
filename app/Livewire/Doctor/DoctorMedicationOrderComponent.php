<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use App\Models\Encounter;
use App\Models\MedicationOrder;
use App\Models\MedicationOrderItem;
use App\Models\PharmacyItem;
use App\Models\CustomMedication;
use App\Models\PharmacyFrequency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DoctorMedicationOrderComponent extends Component
{
    public Encounter $encounter;
    public ?MedicationOrder $order = null;
    
    // Search
    public string $searchQuery = '';
    public $searchResults = [];
    public $customSearchResults = [];
    
    // Order properties
    public $orderType = 'internal';
    public $discountType = null;
    #[Validate('nullable|numeric|min:0')]
    public $discountValue = 0;
    public $notes = '';
    
    // Item form
    public $editingItemId = null;
    #[Validate('required|in:standard,custom')]
    public $itemType = 'standard';
    #[Validate('required_if:itemType,standard')]
    public $drugId = '';
    #[Validate('required_if:itemType,custom')]
    public $customMedicationId = '';
    #[Validate('required|string|max:100')]
    public $dosage = '';
    #[Validate('nullable|exists:pharmacy_frequencies,id')]
    public $frequencyId = '';
    #[Validate('required|string|max:50')]
    public $duration = '';
    #[Validate('nullable|string')]
    public $instructions = '';
    #[Validate('required|numeric|min:1')]
    public $quantity = 1;
    #[Validate('required|numeric|min:0')]
    public $unitPrice = 0;
    public $itemDiscountType = null;
    #[Validate('nullable|numeric|min:0')]
    public $itemDiscountValue = 0;
    
    // UI state
    public bool $showAddItemModal = false;
    public bool $showCustomMedicationModal = false;
    public bool $showSubmitConfirm = false;
    
    // Calculations
    public $subtotal = 0;
    public $totalDiscount = 0;
    public $totalAmount = 0;
    public $payableAmount = 0;
    
    protected $listeners = [
        'customMedicationCreated' => 'handleCustomMedicationCreated'
    ];
    
    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        
        // Verify doctor has access to this encounter
        if ($encounter->doctor_id !== Auth::id()) {
            abort(403, 'You do not have access to this encounter.');
        }
        
        // Load or create draft order
        $this->order = MedicationOrder::firstOrCreate(
            [
                'encounter_id' => $encounter->id,
                'status' => 'draft'
            ],
            [
                'order_type' => 'internal',
                'doctor_id' => Auth::id()
            ]
        );
        
        $this->calculateTotals();
    }
    
    public function searchMedications()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            $this->customSearchResults = [];
            return;
        }
        
        // Search standard medications
        $this->searchResults = PharmacyItem::where('is_active', true)
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('code', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('generic_name', 'like', '%' . $this->searchQuery . '%');
            })
            ->with(['category', 'unit', 'route'])
            ->limit(10)
            ->get()
            ->map(function ($item) {
                $stock = $item->batches()->sum('quantity');
                
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'generic_name' => $item->generic_name,
                    'strength' => $item->strength,
                    'unit' => $item->unit->short_name,
                    'route' => $item->route->short_name,
                    'selling_price' => $item->batches()->avg('selling_price') ?? 0,
                    'stock' => $stock,
                    'type' => 'standard'
                ];
            });
        
        // Search custom medications
        $this->customSearchResults = CustomMedication::where('is_active', true)
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('ingredients', 'like', '%' . $this->searchQuery . '%');
            })
            ->with(['frequency'])
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'ingredients' => $item->ingredients,
                    'dosage' => $item->dosage,
                    'frequency' => $item->frequency->name,
                    'duration' => $item->duration,
                    'base_price' => $item->base_price,
                    'type' => 'custom'
                ];
            });
    }
    
    public function updatedSearchQuery()
    {
        $this->searchMedications();
    }
    
    public function selectMedication($medication)
    {
        $this->itemType = $medication['type'];
        
        if ($medication['type'] === 'standard') {
            $this->drugId = $medication['id'];
            $this->unitPrice = $medication['selling_price'];
            $this->dosage = $medication['strength'];
        } else {
            $this->customMedicationId = $medication['id'];
            $this->unitPrice = $medication['base_price'];
            $this->dosage = $medication['dosage'];
            $this->frequencyId = PharmacyFrequency::where('name', $medication['frequency'])->first()?->id ?? '';
            $this->duration = $medication['duration'];
        }
        
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->customSearchResults = [];
        
        $this->showAddItemModal = true;
    }
    
    public function addItem()
    {
        $this->validate();
        
        $totalPrice = $this->quantity * $this->unitPrice;
        $discountedPrice = $this->calculateDiscountedPrice($totalPrice, $this->itemDiscountType, $this->itemDiscountValue);
        
        $data = [
            'drug_id' => $this->itemType === 'standard' ? $this->drugId : null,
            'custom_medication_id' => $this->itemType === 'custom' ? $this->customMedicationId : null,
            'dosage' => $this->dosage,
            'frequency_id' => $this->frequencyId,
            'duration' => $this->duration,
            'instructions' => $this->instructions,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'discount_type' => $this->itemDiscountType,
            'discount_value' => $this->itemDiscountValue,
            'total_price' => $discountedPrice
        ];
        
        if ($this->editingItemId) {
            $item = MedicationOrderItem::find($this->editingItemId);
            $item->update($data);
        } else {
            $data['medication_order_id'] = $this->order->id;
            MedicationOrderItem::create($data);
        }
        
        $this->resetItemForm();
        $this->calculateTotals();
        $this->showAddItemModal = false;
        
        $this->dispatch('item-saved');
    }
    
    public function editItem($itemId)
    {
        $item = MedicationOrderItem::with(['drug', 'customMedication', 'frequency'])
            ->findOrFail($itemId);
        
        $this->editingItemId = $itemId;
        $this->itemType = $item->drug_id ? 'standard' : 'custom';
        $this->drugId = $item->drug_id;
        $this->customMedicationId = $item->custom_medication_id;
        $this->dosage = $item->dosage;
        $this->frequencyId = $item->frequency_id;
        $this->duration = $item->duration;
        $this->instructions = $item->instructions;
        $this->quantity = $item->quantity;
        $this->unitPrice = $item->unit_price;
        $this->itemDiscountType = $item->discount_type;
        $this->itemDiscountValue = $item->discount_value;
        
        $this->showAddItemModal = true;
    }
    
    public function removeItem($itemId)
    {
        $item = MedicationOrderItem::find($itemId);
        if ($item) {
            $item->delete();
            $this->calculateTotals();
            $this->dispatch('item-removed');
        }
    }
    
    public function calculateTotals()
    {
        $items = $this->order->items()->get();
        
        $this->subtotal = $items->sum('total_price');
        
        // Calculate order level discount
        $orderDiscount = 0;
        if ($this->discountType === 'percentage' && $this->discountValue > 0) {
            $orderDiscount = ($this->subtotal * $this->discountValue) / 100;
        } elseif ($this->discountType === 'fixed' && $this->discountValue > 0) {
            $orderDiscount = min($this->discountValue, $this->subtotal);
        }
        
        // Calculate item discounts
        $itemDiscounts = $items->sum(function ($item) {
            $originalPrice = $item->quantity * $item->unit_price;
            return $originalPrice - $item->total_price;
        });
        
        $this->totalDiscount = $orderDiscount + $itemDiscounts;
        $this->totalAmount = $this->subtotal;
        $this->payableAmount = $this->subtotal - $orderDiscount;
        
        // Update order totals
        $this->order->update([
            'total_amount' => $this->totalAmount,
            'discount_amount' => $this->totalDiscount,
            'payable_amount' => $this->payableAmount,
            'discount_type' => $this->discountType,
            'discount_value' => $this->discountValue,
        ]);
    }
    
    public function calculateDiscountedPrice($price, $discountType, $discountValue)
    {
        if (!$discountType || !$discountValue) {
            return $price;
        }
        
        if ($discountType === 'percentage') {
            return $price - ($price * $discountValue / 100);
        } elseif ($discountType === 'fixed') {
            return max(0, $price - $discountValue);
        }
        
        return $price;
    }
    
    public function updatedDiscountType()
    {
        $this->calculateTotals();
    }
    
    public function updatedDiscountValue()
    {
        $this->calculateTotals();
    }
    
    public function submitOrder()
    {
        if ($this->order->items()->count() === 0) {
            $this->dispatch('error', message: 'Please add at least one medication to the order.');
            return;
        }
        
        $this->order->update([
            'status' => 'ordered',
            'ordered_at' => now()
        ]);
        
        $this->showSubmitConfirm = false;
        $this->dispatch('order-submitted', orderId: $this->order->id);
        
        session()->flash('success', 'Medication order submitted successfully!');
    }
    
    public function handleCustomMedicationCreated($medicationId)
    {
        // Close modal and optionally add to order
        $this->showCustomMedicationModal = false;
        
        // You can auto-select the new custom medication if desired
        // $medication = CustomMedication::find($medicationId);
        // $this->selectMedication([...]);
    }
    
    public function resetItemForm()
    {
        $this->editingItemId = null;
        $this->reset([
            'itemType', 'drugId', 'customMedicationId', 'dosage',
            'frequencyId', 'duration', 'instructions', 'quantity',
            'unitPrice', 'itemDiscountType', 'itemDiscountValue'
        ]);
    }
    
    public function render()
    {
        $frequencies = PharmacyFrequency::all();
        $items = $this->order->items()
            ->with(['drug', 'customMedication', 'frequency'])
            ->get();
        
        return view('livewire.doctor.doctor-medication-order-component', [
            'frequencies' => $frequencies,
            'items' => $items
        ]);
    }
}

    