<?php

namespace App\Livewire\Pharmacy\Batch;

use Livewire\Component;
use App\Models\PharmacyBatch;
use App\Models\PharmacyItem;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $medicine_id;
    public $batch_number;
    public $expiry_date;
    public $quantity;
    public $purchase_price;
    public $selling_price;
    public $editingId = null;
    public $search = '';

    protected $rules = [
        'medicine_id' => 'required|exists:pharmacy_items,id',
        'batch_number' => 'required|string',
        'expiry_date' => 'required|date',
        'quantity' => 'required|integer|min:0',
        'purchase_price' => 'nullable|numeric|min:0',
        'selling_price' => 'nullable|numeric|min:0',
    ];

    public function save()
    {
        $this->validate();

        PharmacyBatch::updateOrCreate(
            ['id' => $this->editingId],
            [
                'medicine_id' => $this->medicine_id,
                'batch_number' => $this->batch_number,
                'expiry_date' => $this->expiry_date,
                'quantity' => $this->quantity,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->selling_price,
            ]
        );

        $this->reset(['medicine_id','batch_number','expiry_date','quantity','purchase_price','selling_price','editingId']);
        session()->flash('message', 'Batch saved successfully.');
    }

    public function edit($id)
    {
        $batch = PharmacyBatch::findOrFail($id);

        $this->editingId = $id;
        $this->medicine_id = $batch->medicine_id;
        $this->batch_number = $batch->batch_number;
        $this->expiry_date = $batch->expiry_date->format('Y-m-d');
        $this->quantity = $batch->quantity;
        $this->purchase_price = $batch->purchase_price;
        $this->selling_price = $batch->selling_price;
    }

    public function delete($id)
    {
        PharmacyBatch::findOrFail($id)->delete();
        session()->flash('message', 'Batch deleted successfully.');
    }

    public function render()
    {
        $batches = PharmacyBatch::with('medicine')
            ->whereHas('medicine', function($q) {
                $q->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('expiry_date', 'asc')
            ->paginate(10);

        $medicines = PharmacyItem::where('is_active', 1)->get();

        return view('livewire.pharmacy.batch.index', compact('batches','medicines'));
    }
}
