<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use App\Models\CuppingLocation;
use App\Models\CuppingTherapy;
use App\Models\CuppingTherapyItem;
use App\Models\CuppingType;
use App\Models\Encounter;
use Illuminate\Support\Facades\DB;

class CuppingOrderForm extends Component
{
      public $encounter;
    public $items = [];
    public $notes = '';
    public $discount = 0;
    public $grandTotal = 0;
    public $finalTotal = 0;
    
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
        $this->encounter = $encounter;
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
    }
    
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }
    
    public function updatedItems($value, $key)
    {
        $this->calculateTotals();
    }
    
    public function updatedDiscount()
    {
        $this->calculateTotals();
    }
    
    public function calculateTotals()
    {
        $this->grandTotal = 0;
        
        foreach ($this->items as $index => $item) {
            $qty = (int)($item['qty'] ?? 0);
            $price = (float)($item['price'] ?? 0);
            $total = $qty * $price;
            
            $this->items[$index]['total'] = $total;
            $this->grandTotal += $total;
        }
        
        $discount = (float)$this->discount;
        $this->finalTotal = max(0, $this->grandTotal - $discount);
    }
    
    public function save()
    {
        $this->validate();
        
        if (count($this->items) == 0) {
            $this->addError('items', 'Please add at least one item');
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
            
            session()->flash('message', 'Cupping order placed successfully!');
            return redirect()->route('encounter.show', $this->encounter->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error saving cupping order: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.cupping.cupping-order-form');
    }
}
