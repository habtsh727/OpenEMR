<?php

namespace App\Livewire\Cupping;

use App\Models\CuppingLocation;
use App\Models\CuppingTherapy;
use App\Models\CuppingTherapyItem;
use App\Models\CuppingType;
use App\Models\Encounter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CuppingOrderForm extends Component
{
    public Encounter $encounter;
    public $items = [];
    public $notes = '';
    public $discount = 0;
    public $grandTotal = 0;
    public $finalTotal = 0;
    public $isSubmitting = false;

    protected $rules = [
        'items.*.cupping_type_id' => 'required|exists:cupping_types,id',
        'items.*.cupping_location_id' => 'required|exists:cupping_locations,id',
        'items.*.qty' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.notes' => 'nullable|string',
        'notes' => 'nullable|string',
        'discount' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'items.*.cupping_type_id.required' => 'Please select a cupping type',
        'items.*.cupping_location_id.required' => 'Please select a cupping location',
        'items.*.qty.required' => 'Please enter quantity',
        'items.*.qty.min' => 'Quantity must be at least 1',
        'items.*.price.required' => 'Please enter price',
        'items.*.price.min' => 'Price cannot be negative',
        'discount.min' => 'Discount cannot be negative',
    ];

    public function mount(Encounter $encounter)
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to place cupping orders.');
        }

        $this->encounter = $encounter->load(['patient', 'doctor']);
        $this->addItem();
        $this->calculateTotals();
    }

    public function addItem()
    {
        $this->items[] = [
            'cupping_type_id' => '',
            'cupping_location_id' => '',
            'qty' => 1,
            'price' => 0,
            'total' => 0,
            'notes' => '',
        ];
        $this->calculateTotals();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    // Listen for changes on specific fields
    public function updatedItems($value, $key)
    {
        $this->calculateTotals();
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    // Method to recalculate all totals
    public function calculateTotals()
    {
        $this->grandTotal = 0;
        
        foreach ($this->items as $index => $item) {
            $qty = (int)($item['qty'] ?? 0);
            $price = (float)($item['price'] ?? 0);
            $total = $qty * $price;
            
            // Update the total for this item
            $this->items[$index]['total'] = $total;
            $this->grandTotal += $total;
        }
        
        $discount = (float)$this->discount;
        $this->finalTotal = max(0, $this->grandTotal - $discount);
    }

    public function save()
    {
        $this->isSubmitting = true;
        
        $this->validate();
        
        if (count($this->items) == 0) {
            $this->addError('items', 'Please add at least one item');
            $this->isSubmitting = false;
            return;
        }
        
        try {
            DB::beginTransaction();
            
            // Create Cupping Therapy
            $cuppingTherapy = CuppingTherapy::create([
                'encounter_id' => $this->encounter->id,
                'notes' => $this->notes,
                'total_amount' => $this->grandTotal,
                'discount' => $this->discount,
                'final_amount' => $this->finalTotal,
                'status' => 'order_placed',
            ]);
            
            // Create Cupping Therapy Items
            foreach ($this->items as $item) {
                CuppingTherapyItem::create([
                    'cupping_therapy_id' => $cuppingTherapy->id,
                    'cupping_type_id' => $item['cupping_type_id'],
                    'cupping_location_id' => $item['cupping_location_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
            
            DB::commit();
            
            session()->flash('success', 'Cupping order placed successfully!');
            $this->dispatch('order-placed');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving cupping order: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function saveDraft()
    {
        $this->isSubmitting = true;
        
        if (count($this->items) == 0) {
            session()->flash('info', 'No items to save. Please add at least one item.');
            $this->isSubmitting = false;
            return;
        }
        
        try {
            DB::beginTransaction();
            
            $cuppingTherapy = CuppingTherapy::create([
                'encounter_id' => $this->encounter->id,
                'notes' => $this->notes,
                'total_amount' => $this->grandTotal,
                'discount' => $this->discount,
                'final_amount' => $this->finalTotal,
                'status' => 'pending',
            ]);
            
            foreach ($this->items as $item) {
                CuppingTherapyItem::create([
                    'cupping_therapy_id' => $cuppingTherapy->id,
                    'cupping_type_id' => $item['cupping_type_id'],
                    'cupping_location_id' => $item['cupping_location_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
            
            DB::commit();
            
            session()->flash('success', 'Draft saved successfully! You can continue later.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving draft: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function back()
    {
        return $this->redirect(route('consultation.examination', $this->encounter), navigate: true);
    }

    public function render()
    {
        $cuppingTypes = CuppingType::where('status', true)
            ->orderBy('name')
            ->get();
            
        $cuppingLocations = CuppingLocation::where('status', true)
            ->orderBy('name')
            ->get();
        
        return view('livewire.cupping.cupping-order-form', [
            'cuppingTypes' => $cuppingTypes,
            'cuppingLocations' => $cuppingLocations,
        ]);
    }
}