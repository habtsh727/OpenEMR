<?php

namespace App\Livewire\Rehab;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RehabPackage;
use App\Models\RehabPackageItem;
use App\Models\PharmacyFrequency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RehabPackagesComponent extends Component
{
  use WithPagination;

    // Search & Filters
    public $search = '';
    public $status = 'active';
    public $perPage = 15;

    // Form Properties
    public $showForm = false;
    public $editingId = null;
    public $formTitle = 'Create Rehab Package';

    // Package Properties
    public $name = '';
    public $description = '';
    public $base_price = 0;
    public $discount_type = null;
    public $discount_value = null;
    public $final_price = 0;
    public $is_active = true;

    // Items Collection
    public $items = [];
    public $itemTypes;

    // Item Form Properties
    public $editingItemIndex = null;
    public $showItemForm = false;
    public $itemFormTitle = 'Add Item';

    // Single Item Properties
    public $item_type = '';
    public $item_name = '';
    public $dosage = '';
    public $frequency_id = '';
    public $duration = '';
    public $bed_duration_days = '';
    public $notes = '';

    // Stats
    public $stats = [];

    protected $listeners = [
        'refresh' => '$refresh',
        'confirm-delete' => 'deletePackage',
        'confirm-delete-item' => 'deleteItem'
    ];

    public function mount()
    {
        $this->itemTypes = RehabPackageItem::TYPES;
        $this->loadStats();
        $this->resetItemForm();
    }

    public function updatedBasePrice()
    {
        $this->calculateFinalPrice();
    }

    public function updatedDiscountType()
    {
        $this->calculateFinalPrice();
    }

    public function updatedDiscountValue()
    {
        $this->calculateFinalPrice();
    }

    protected function calculateFinalPrice()
    {
        if (!$this->discount_type || !$this->discount_value || $this->discount_value <= 0) {
            $this->final_price = $this->base_price;
            return;
        }

        $basePrice = floatval($this->base_price);
        $discountValue = floatval($this->discount_value);

        if ($this->discount_type === 'fixed') {
            $this->final_price = max(0, $basePrice - $discountValue);
        } elseif ($this->discount_type === 'percentage') {
            $discountAmount = ($basePrice * $discountValue) / 100;
            $this->final_price = max(0, $basePrice - $discountAmount);
        } else {
            $this->final_price = $basePrice;
        }

        $this->final_price = round($this->final_price, 2);
    }

    public function loadStats()
    {
        $this->stats = [
            'total' => RehabPackage::count(),
            'active' => RehabPackage::where('is_active', true)->count(),
            'inactive' => RehabPackage::where('is_active', false)->count(),
            'total_items' => RehabPackageItem::count(),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->formTitle = 'Create Rehab Package';
        $this->showForm = true;
    }

    public function showEditForm($id)
    {
        $package = RehabPackage::with('items')->findOrFail($id);

        $this->editingId = $id;
        $this->name = $package->name;
        $this->description = $package->description;
        $this->base_price = $package->base_price;
        $this->discount_type = $package->discount_type;
        $this->discount_value = $package->discount_value;
        $this->final_price = $package->final_price;
        $this->is_active = $package->is_active;

        // Load items
        $this->items = $package->items->map(function ($item) {
            return [
                'id' => $item->id,
                'item_type' => $item->item_type,
                'item_name' => $item->item_name,
                'dosage' => $item->dosage,
                'frequency_id' => $item->frequency_id,
                'frequency_name' => $item->frequency?->name,
                'duration' => $item->duration,
                'bed_duration_days' => $item->bed_duration_days,
                'notes' => $item->notes,
                'type_label' => $item->type_label,
                'type_color' => $item->type_color,
                'display_name' => $item->display_name,
            ];
        })->toArray();

        $this->formTitle = 'Edit Rehab Package';
        $this->showForm = true;
    }

    public function savePackage()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0|required_with:discount_type',
            'is_active' => 'boolean',
        ];

        $this->validate($rules);

        if ($this->discount_type === 'percentage' && $this->discount_value > 100) {
            $this->addError('discount_value', 'Percentage discount cannot exceed 100%');
            return;
        }

        DB::beginTransaction();

        try {
            $data = [
                'name' => $this->name,
                'description' => $this->description,
                'base_price' => $this->base_price,
                'discount_type' => $this->discount_type,
                'discount_value' => $this->discount_value,
                'is_active' => $this->is_active,
            ];

            if ($this->editingId) {
                $package = RehabPackage::findOrFail($this->editingId);
                $package->update($data);
                $message = 'Package updated successfully!';
            } else {
                $package = RehabPackage::create($data);
                $message = 'Package created successfully!';
            }

            // Sync items
            $this->syncPackageItems($package);

            DB::commit();

            $this->resetForm();
            $this->showForm = false;
            $this->loadStats();

            session()->flash('success', $message);
            $this->dispatch('package-updated');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to save package: ' . $e->getMessage());
        }
    }

    protected function syncPackageItems($package)
    {
        // Get existing item IDs
        $existingItemIds = $package->items()->pluck('id')->toArray();
        $submittedItemIds = collect($this->items)->pluck('id')->filter()->toArray();

        // Delete removed items
        $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
        if (!empty($itemsToDelete)) {
            RehabPackageItem::whereIn('id', $itemsToDelete)->delete();
        }

        // Update or create items
        foreach ($this->items as $itemData) {
            $item = [
                'rehab_package_id' => $package->id,
                'item_type' => $itemData['item_type'],
                'item_name' => $itemData['item_name'],
                'dosage' => $itemData['dosage'] ?? null,
                'frequency_id' => $itemData['frequency_id'] ?? null,
                'duration' => $itemData['duration'] ?? null,
                'bed_duration_days' => $itemData['bed_duration_days'] ?? null,
                'notes' => $itemData['notes'] ?? null,
            ];

            if (isset($itemData['id']) && $itemData['id']) {
                RehabPackageItem::where('id', $itemData['id'])->update($item);
            } else {
                RehabPackageItem::create($item);
            }
        }
    }

    public function showItemFormModal($index = null)
    {
        $this->resetItemForm();

        if ($index !== null && isset($this->items[$index])) {
            $this->editingItemIndex = $index;
            $item = $this->items[$index];

            $this->item_type = $item['item_type'];
            $this->item_name = $item['item_name'];
            $this->dosage = $item['dosage'] ?? '';
            $this->frequency_id = $item['frequency_id'] ?? '';
            $this->duration = $item['duration'] ?? '';
            $this->bed_duration_days = $item['bed_duration_days'] ?? '';
            $this->notes = $item['notes'] ?? '';

            $this->itemFormTitle = 'Edit Item';
        } else {
            $this->itemFormTitle = 'Add Item';
        }

        $this->showItemForm = true;
    }

    public function saveItem()
    {
        $rules = [
            'item_type' => 'required|in:' . implode(',', array_keys($this->itemTypes)),
            'item_name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'frequency_id' => 'nullable|exists:pharmacy_frequencies,id',
            'duration' => 'nullable|string|max:50',
            'bed_duration_days' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ];

        $this->validate($rules);

        $itemData = [
            'item_type' => $this->item_type,
            'item_name' => $this->item_name,
            'dosage' => $this->dosage,
            'frequency_id' => $this->frequency_id,
            'duration' => $this->duration,
            'bed_duration_days' => $this->bed_duration_days,
            'notes' => $this->notes,
        ];

        if ($this->editingItemIndex !== null) {
            // Update existing item
            $this->items[$this->editingItemIndex] = array_merge(
                $this->items[$this->editingItemIndex],
                $itemData
            );
        } else {
            // Add new item
            $this->items[] = $itemData;
        }

        $this->resetItemForm();
        $this->showItemForm = false;
        $this->editingItemIndex = null;
    }

    public function resetItemForm()
    {
        $this->item_type = '';
        $this->item_name = '';
        $this->dosage = '';
        $this->frequency_id = '';
        $this->duration = '';
        $this->bed_duration_days = '';
        $this->notes = '';
    }

    public function confirmDeleteItem($index)
    {
        if (isset($this->items[$index])) {
            $item = $this->items[$index];
            $itemName = $item['item_name'];

            $this->dispatch('confirm-delete-item-modal',
                title: 'Delete Item',
                message: "Are you sure you want to delete '{$itemName}' from this package?",
                confirmText: 'Delete',
                cancelText: 'Cancel',
                index: $index
            );
        }
    }

    public function deleteItem($index)
    {
        if (isset($this->items[$index])) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Reindex array
            session()->flash('success', 'Item removed from package.');
        }
    }

    public function toggleStatus($id)
    {
        $package = RehabPackage::findOrFail($id);
        $package->update(['is_active' => !$package->is_active]);

        $status = $package->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Package {$status} successfully!");
        $this->loadStats();
        $this->dispatch('package-updated');
    }

    public function confirmDelete($id)
    {
        $package = RehabPackage::findOrFail($id);

        $this->dispatch('confirm-delete-modal',
            title: 'Delete Package',
            message: "Are you sure you want to delete '{$package->name}'? This will also delete all items in this package.",
            confirmText: 'Delete',
            cancelText: 'Cancel',
            id: $id
        );
    }

    public function deletePackage($id)
    {
        try {
            $package = RehabPackage::findOrFail($id);
            $package->delete();

            session()->flash('success', 'Package deleted successfully!');
            $this->loadStats();
            $this->dispatch('package-updated');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete package: ' . $e->getMessage());
        }
    }

    public function duplicatePackage($id)
    {
        $original = RehabPackage::with('items')->findOrFail($id);

        $this->resetForm();
        $this->name = $original->name . ' (Copy)';
        $this->description = $original->description;
        $this->base_price = $original->base_price;
        $this->discount_type = $original->discount_type;
        $this->discount_value = $original->discount_value;
        $this->is_active = true;

        // Duplicate items
        $this->items = $original->items->map(function ($item) {
            return [
                'item_type' => $item->item_type,
                'item_name' => $item->item_name,
                'dosage' => $item->dosage,
                'frequency_id' => $item->frequency_id,
                'duration' => $item->duration,
                'bed_duration_days' => $item->bed_duration_days,
                'notes' => $item->notes,
            ];
        })->toArray();

        $this->formTitle = 'Duplicate Package';
        $this->showForm = true;
    }

    public function resetForm()
    {
        $this->reset([
            'editingId',
            'name',
            'description',
            'base_price',
            'discount_type',
            'discount_value',
            'final_price',
            'is_active',
            'items',
            'editingItemIndex',
            'showItemForm',
        ]);

        $this->is_active = true;
        $this->base_price = 0;
        $this->final_price = 0;
        $this->items = [];
    }

    public function getFrequenciesProperty()
    {
        return PharmacyFrequency::orderBy('name')->get();
    }

    public function render()
    {
        $packages = RehabPackage::with(['creator', 'items'])
            ->when($this->status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.rehab.rehab-packages-component', [
            'packages' => $packages,
        ]);
    }
}
