<?php

namespace App\Livewire\Forms;
use App\Models\RehabPackage;
use App\Models\RehabPackageItem;
use App\Models\Frequency;
use App\Models\PharmacyFrequency;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RehabPackageForm extends Component
{
    public $packageId = null;
    public $name = '';
    public $description = '';
    public $base_price = 0;
    public $discount_type = null;
    public $discount_value = null;
    public $is_active = true;
    
    public $items = [];
    public $frequencies = [];
    
    public $activeTab = 'medications';
    
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    if ($this->discount_type === 'percentage' && $value > 100) {
                        $fail('Percentage discount cannot exceed 100%.');
                    }
                }
            ],
            'is_active' => 'boolean',
            'items' => 'array',
            'items.*.item_type' => 'required|in:standard_medication,custom_medication,service,bed',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.dosage' => 'required_if:items.*.item_type,standard_medication,custom_medication|nullable|string',
            'items.*.frequency_id' => 'required_if:items.*.item_type,standard_medication,custom_medication|nullable|exists:frequencies,id',
            'items.*.duration' => 'required_if:items.*.item_type,standard_medication,custom_medication|nullable|string',
            'items.*.bed_duration_days' => 'required_if:items.*.item_type,bed|nullable|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ];
    }

    public function mount($id = null)
    {
        $this->frequencies = PharmacyFrequency::all();
        
        
        if ($id) {
            $this->packageId = $id;
            $package = RehabPackage::with('items')->findOrFail($id);
            
            $this->name = $package->name;
            $this->description = $package->description;
            $this->base_price = $package->base_price;
            $this->discount_type = $package->discount_type;
            $this->discount_value = $package->discount_value;
            $this->is_active = $package->is_active;
            
            foreach ($package->items as $item) {
                $this->items[] = [
                    'id' => $item->id,
                    'item_type' => $item->item_type,
                    'item_name' => $item->item_name,
                    'dosage' => $item->dosage,
                    'frequency_id' => $item->frequency_id,
                    'duration' => $item->duration,
                    'bed_duration_days' => $item->bed_duration_days,
                    'notes' => $item->notes,
                ];
            }
        }
    }

    public function addItem($type)
    {
        $this->items[] = [
            'item_type' => $type,
            'item_name' => '',
            'dosage' => '',
            'frequency_id' => null,
            'duration' => '',
            'bed_duration_days' => null,
            'notes' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateFinalPrice()
    {
        $finalPrice = $this->base_price;
        
        if ($this->discount_type && $this->discount_value) {
            if ($this->discount_type === 'fixed') {
                $finalPrice = $this->base_price - $this->discount_value;
            } elseif ($this->discount_type === 'percentage') {
                $finalPrice = $this->base_price - ($this->base_price * $this->discount_value / 100);
            }
        }
        
        return max(0, $finalPrice);
    }

    public function getBedCountProperty()
    {
        return collect($this->items)->filter(function ($item) {
            return $item['item_type'] === 'bed';
        })->count();
    }

    public function save()
    {
        $this->validate();
        
        // Check for duplicate bed
        $bedCount = $this->bed_count;
        if ($bedCount > 1) {
            session()->flash('error', 'Only one bed entry is allowed per package.');
            return;
        }
        
        DB::transaction(function () {
            $finalPrice = $this->calculateFinalPrice();
            
            if ($this->packageId) {
                $package = RehabPackage::findOrFail($this->packageId);
                $package->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'base_price' => $this->base_price,
                    'discount_type' => $this->discount_type,
                    'discount_value' => $this->discount_value,
                    'final_price' => $finalPrice,
                    'is_active' => $this->is_active,
                ]);
                
                // Get existing item IDs
                $existingItemIds = $package->items->pluck('id')->toArray();
                $submittedItemIds = collect($this->items)->pluck('id')->filter()->toArray();
                
                // Delete removed items
                $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
                RehabPackageItem::whereIn('id', $itemsToDelete)->delete();
                
                // Update or create items
                foreach ($this->items as $itemData) {
                    if (isset($itemData['id'])) {
                        RehabPackageItem::where('id', $itemData['id'])->update([
                            'item_type' => $itemData['item_type'],
                            'item_name' => $itemData['item_name'],
                            'dosage' => $itemData['dosage'] ?? null,
                            'frequency_id' => $itemData['frequency_id'] ?? null,
                            'duration' => $itemData['duration'] ?? null,
                            'bed_duration_days' => $itemData['bed_duration_days'] ?? null,
                            'notes' => $itemData['notes'] ?? null,
                        ]);
                    } else {
                        $package->items()->create([
                            'item_type' => $itemData['item_type'],
                            'item_name' => $itemData['item_name'],
                            'dosage' => $itemData['dosage'] ?? null,
                            'frequency_id' => $itemData['frequency_id'] ?? null,
                            'duration' => $itemData['duration'] ?? null,
                            'bed_duration_days' => $itemData['bed_duration_days'] ?? null,
                            'notes' => $itemData['notes'] ?? null,
                        ]);
                    }
                }
            } else {
                $package = RehabPackage::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'base_price' => $this->base_price,
                    'discount_type' => $this->discount_type,
                    'discount_value' => $this->discount_value,
                    'final_price' => $finalPrice,
                    'is_active' => $this->is_active,
                    'created_by' => auth()->id(),
                ]);
                
                foreach ($this->items as $itemData) {
                    $package->items()->create([
                        'item_type' => $itemData['item_type'],
                        'item_name' => $itemData['item_name'],
                        'dosage' => $itemData['dosage'] ?? null,
                        'frequency_id' => $itemData['frequency_id'] ?? null,
                        'duration' => $itemData['duration'] ?? null,
                        'bed_duration_days' => $itemData['bed_duration_days'] ?? null,
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }
        });
        
        session()->flash('message', 'Package saved successfully.');
        return $this->redirect(route('rehab.packages.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.forms.rehab-package-form', [
            'frequencies' => $this->frequencies,
            'finalPrice' => $this->calculateFinalPrice(),
        ]);
    }
}
