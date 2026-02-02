<?php

namespace App\Livewire\Consumables;

use App\Models\Consumable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class ConsumableManager extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $category = '';

    #[Url]
    public $isActive = true;

    public $editingId = null;
    public $showModal = false;
    public $showDeleteModal = false;

    // Form fields
    public $name;
    public $code;
    public $category_id;
    public $unit;
    public $current_stock = 0;
    public $minimum_stock = 0;
    public $unit_cost;
    public $billable = false;
    public $is_active = true;

    // Available options
    public $categories = ['oxygen', 'iv-fluid', 'disposable', 'dressing', 'balm'];
    public $units = ['pcs', 'ml', 'liter', 'cylinder', 'box', 'pack'];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:consumables,code',
            'category' => 'nullable|string|in:' . implode(',', $this->categories),
            'unit' => 'required|string|in:' . implode(',', $this->units),
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0|max:99999999.99',
            'billable' => 'boolean',
            'is_active' => 'boolean',
        ];

        if ($this->editingId) {
            $rules['code'] = 'required|string|max:100|unique:consumables,code,' . $this->editingId;
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'name.required' => 'The consumable name is required.',
            'code.required' => 'The unique code is required.',
            'code.unique' => 'This code is already in use.',
            'unit.required' => 'Please select a unit of measurement.',
            'current_stock.required' => 'Current stock is required.',
            'minimum_stock.required' => 'Minimum stock level is required.',
            'unit_cost.numeric' => 'Unit cost must be a valid number.',
        ];
    }

    public function mount()
    {
        // No permission check - accessible to all authenticated users
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedIsActive()
    {
        $this->resetPage();
    }

    public function openModal($id = null)
    {
        $this->editingId = $id;
        
        if ($id) {
            $consumable = Consumable::findOrFail($id);
            $this->fill($consumable->toArray());
        } else {
            $this->resetForm();
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingId = null;
    }

    public function confirmDelete($id)
    {
        $this->editingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $consumable = Consumable::findOrFail($this->editingId);
        
        // Check for existing transactions
        if (method_exists($consumable, 'stockTransactions') && $consumable->stockTransactions()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Cannot delete consumable with existing stock transactions.'
            ]);
            $this->showDeleteModal = false;
            return;
        }

        $consumable->delete();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Consumable deleted successfully!'
        ]);
        
        $this->showDeleteModal = false;
        $this->editingId = null;
    }

    private function resetForm()
    {
        $this->reset([
            'name',
            'code',
            'category',
            'unit',
            'current_stock',
            'minimum_stock',
            'unit_cost',
            'billable',
            'is_active',
        ]);
        
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'code' => strtoupper($this->code),
            'category' => $this->category,
            'unit' => $this->unit,
            'current_stock' => $this->current_stock,
            'minimum_stock' => $this->minimum_stock,
            'unit_cost' => $this->unit_cost,
            'billable' => $this->billable,
            'is_active' => $this->is_active,
            'updated_by' => Auth::id(),
        ];

        if ($this->editingId) {
            $consumable = Consumable::findOrFail($this->editingId);
            $consumable->update($data);
            $message = 'Consumable updated successfully!';
        } else {
            $data['created_by'] = Auth::id();
            Consumable::create($data);
            $message = 'Consumable created successfully!';
        }

        $this->closeModal();
        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
    }

    public function getStockStatus($currentStock, $minimumStock)
    {
        if ($currentStock <= 0) {
            return ['status' => 'out-of-stock', 'label' => 'Out of Stock', 'class' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'];
        } elseif ($currentStock <= $minimumStock) {
            return ['status' => 'low-stock', 'label' => 'Low Stock', 'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'];
        } else {
            return ['status' => 'in-stock', 'label' => 'In Stock', 'class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'];
        }
    }

    public function render()
    {
        $query = Consumable::query()
            ->with(['creator', 'updater'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($q) {
                $q->where('category', $this->category);
            })
            ->when($this->isActive !== '', function ($q) {
                $q->where('is_active', $this->isActive);
            })
            ->latest();
        
        $consumables = $query->paginate(10);

        return view('livewire.consumables.consumable-manager', [
            'consumables' => $consumables,
        ]);
    }
}