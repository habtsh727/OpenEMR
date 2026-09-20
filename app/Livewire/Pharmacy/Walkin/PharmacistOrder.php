<?php

namespace App\Livewire\Pharmacy\Walkin;

use Livewire\Component;
use App\Models\PharmacyItem;
use App\Models\PharmacyBatch;
use App\Models\WalkinOrder;
use App\Models\WalkinOrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class PharmacistOrder extends Component
{
    use WithPagination;

    // Search
    public $search = '';
    public $selectedMedicine = null;

    // Cart
    public $cart = [];
    public $customerName = '';
    public $customerPhone = '';
    public $notes = '';

    // Selected batch for medicine
    public $selectedBatch = null;
    public $batches = [];
    public $quantity = 1;

    // Current order
    public $currentOrder = null;
    public $showSuccessModal = false;
    public $generatedOrderNumber = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function selectMedicine($medicineId)
    {
        $this->selectedMedicine = PharmacyItem::with(['batches' => function($q) {
            $q->where('is_active', true)
              ->where('quantity', '>', 0)
              ->where('expiry_date', '>', now())
              ->orderBy('expiry_date', 'asc');
        }])->find($medicineId);

        $this->batches = $this->selectedMedicine->batches;
        $this->selectedBatch = null;
        $this->quantity = 1;
    }

    public function addToCart()
    {
        if (!$this->selectedBatch || !$this->quantity) {
            session()->flash('error', 'Please select batch and quantity');
            return;
        }

        $batch = PharmacyBatch::find($this->selectedBatch);

        if ($this->quantity > $batch->quantity) {
            session()->flash('error', 'Not enough stock. Available: ' . $batch->quantity);
            return;
        }

        // Check if already in cart
        $existingIndex = collect($this->cart)->search(function($item) use ($batch) {
            return $item['batch_id'] == $batch->id;
        });

        if ($existingIndex !== false) {
            // Update existing
            $newQuantity = $this->cart[$existingIndex]['quantity'] + $this->quantity;
            if ($newQuantity > $batch->quantity) {
                session()->flash('error', 'Total quantity exceeds available stock');
                return;
            }
            $this->cart[$existingIndex]['quantity'] = $newQuantity;
            $this->cart[$existingIndex]['total_price'] = $newQuantity * $this->cart[$existingIndex]['unit_price'];
        } else {
            // Add new item
            $this->cart[] = [
                'medicine_id' => $this->selectedMedicine->id,
                'medicine_name' => $this->selectedMedicine->name,
                'strength' => $this->selectedMedicine->strength,
                'batch_id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'quantity' => $this->quantity,
                'unit_price' => $batch->selling_price,
                'total_price' => $this->quantity * $batch->selling_price,
            ];
        }

        // Reset selections
        $this->selectedMedicine = null;
        $this->batches = [];
        $this->selectedBatch = null;
        $this->quantity = 1;

        session()->flash('success', 'Item added to cart');
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        session()->flash('success', 'Item removed from cart');
    }

    public function updateCartQuantity($index, $quantity)
    {
        if ($quantity < 1) {
            $this->removeFromCart($index);
            return;
        }

        $batch = PharmacyBatch::find($this->cart[$index]['batch_id']);

        if ($quantity > $batch->quantity) {
            session()->flash('error', 'Not enough stock. Available: ' . $batch->quantity);
            return;
        }

        $this->cart[$index]['quantity'] = $quantity;
        $this->cart[$index]['total_price'] = $quantity * $this->cart[$index]['unit_price'];
    }

    public function getTotalAmountProperty()
    {
        return array_sum(array_column($this->cart, 'total_price'));
    }

   public function submitOrder()
{
    if (empty($this->cart)) {
        session()->flash('error', 'Cart is empty');
        return;
    }

    DB::beginTransaction();

    try {
        // Create order
        $order = WalkinOrder::create([
            'order_number' => WalkinOrder::generateOrderNumber(),
            'customer_name' => $this->customerName ?: 'Walk-in Customer',
            'customer_phone' => $this->customerPhone,
            'status' => 'pending_payment',
            'total_amount' => $this->total_amount,
            'notes' => $this->notes,
            'created_by' => auth()->id(),
        ]);

        // Create order items - using order_id (not walkin_order_id)
        foreach ($this->cart as $item) {
            WalkinOrderItem::create([
                'order_id' => $order->id,  // This is the key - use order_id
                'medicine_id' => $item['medicine_id'],
                'batch_id' => $item['batch_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'status' => 'pending',
            ]);
        }

        DB::commit();

        $this->generatedOrderNumber = $order->order_number;
        $this->showSuccessModal = true;

        // Reset form
        $this->reset(['cart', 'customerName', 'customerPhone', 'notes', 'search']);

    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Failed to create order: ' . $e->getMessage());
    }
}
    public function getMedicinesProperty()
    {
        return PharmacyItem::where('is_active', true)
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('generic_name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.pharmacy.walkin.pharmacist-order', [
            'medicines' => $this->medicines,
            'cart_total' => $this->total_amount,
        ]);
    }
}
