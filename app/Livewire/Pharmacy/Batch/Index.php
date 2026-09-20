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

    // Add search filters
    public $searchByMedicine = true;
    public $searchByBatch = true;
    public $filterExpiringSoon = false;
    public $filterExpired = false;

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

        // Refresh the component
        $this->dispatch('batch-saved');
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->filterExpiringSoon = false;
        $this->filterExpired = false;
        $this->resetPage();
    }

    public function toggleExpiringSoon()
    {
        $this->filterExpiringSoon = !$this->filterExpiringSoon;
        if ($this->filterExpiringSoon) {
            $this->filterExpired = false;
        }
        $this->resetPage();
    }

    public function toggleExpired()
    {
        $this->filterExpired = !$this->filterExpired;
        if ($this->filterExpired) {
            $this->filterExpiringSoon = false;
        }
        $this->resetPage();
    }

    public function render()
    {
        $batches = PharmacyBatch::with('medicine')
            ->when($this->search, function($query) {
                // Search in medicine name OR batch number
                $query->where(function($q) {
                    // Search by batch number directly
                    if ($this->searchByBatch) {
                        $q->orWhere('batch_number', 'like', "%{$this->search}%");
                    }

                    // Search by medicine name through relationship
                    if ($this->searchByMedicine) {
                        $q->orWhereHas('medicine', function($subQuery) {
                            $subQuery->where('name', 'like', "%{$this->search}%")
                                     ->orWhere('generic_name', 'like', "%{$this->search}%")
                                     ->orWhere('code', 'like', "%{$this->search}%")
                                     ->orWhere('strength', 'like', "%{$this->search}%");
                        });
                    }
                });
            })
            ->when($this->filterExpiringSoon, function($query) {
                // Batches expiring in next 30 days
                $query->whereBetween('expiry_date', [now(), now()->addDays(30)])
                      ->where('quantity', '>', 0);
            })
            ->when($this->filterExpired, function($query) {
                // Expired batches
                $query->where('expiry_date', '<', now());
            })
            ->orderBy('expiry_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Keep pagination with search query
        $batches->appends(['search' => $this->search]);

        $medicines = PharmacyItem::where('is_active', 1)
            ->orderBy('name')
            ->get();

        // Get statistics
        $stats = [
            'total_batches' => PharmacyBatch::count(),
            'total_stock' => PharmacyBatch::sum('quantity'),
            'expiring_soon' => PharmacyBatch::whereBetween('expiry_date', [now(), now()->addDays(30)])->sum('quantity'),
            'expired_stock' => PharmacyBatch::where('expiry_date', '<', now())->sum('quantity'),
        ];

        return view('livewire.pharmacy.batch.index', compact('batches', 'medicines', 'stats'));
    }
}
