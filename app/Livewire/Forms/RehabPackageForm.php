<?php

namespace App\Livewire\Forms;


use App\Models\RehabPackage;
use App\Models\RehabPackageItem;
use App\Models\Frequency;
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
    
    // Items collections
    public $standardMedications = [];
    public $customMedications = [];
    public $services = [];
    public $bed = null;
    
    // Frequencies for dropdown (optional - can also be custom)
    public $frequencies = [];
    
    // UI State
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';
    public $editingId = null;
    public $editingType = null;
    public $editingIndex = null;
    
    // Current item being added/edited
    public $currentItem = [
        'item_type' => '',
        'item_name' => '',
        'dosage' => '',
        'frequency' => '',
        'duration' => '',
        'quantity' => '',
        'instructions' => '',
        'notes' => '',
        'bed_duration_days' => null,
    ];
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'base_price' => 'required|numeric|min:0',
        'discount_type' => 'nullable|in:fixed,percentage',
        'discount_value' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
        
        // Current item validation
        'currentItem.item_name' => 'required|string|max:255',
        'currentItem.dosage' => 'required_if:currentItem.item_type,standard_medication,custom_medication|nullable|string',
        'currentItem.frequency' => 'required_if:currentItem.item_type,standard_medication,custom_medication|nullable|string',
        'currentItem.duration' => 'required_if:currentItem.item_type,standard_medication,custom_medication|nullable|string',
        'currentItem.quantity' => 'required_if:currentItem.item_type,standard_medication,custom_medication|nullable|string',
        'currentItem.instructions' => 'nullable|string',
        'currentItem.bed_duration_days' => 'required_if:currentItem.item_type,bed|nullable|integer|min:1',
    ];

    public function mount($id = null)
    {
        $this->frequencies = Frequency::where('is_active', true)->get();
        
        if ($id) {
            $this->packageId = $id;
            $this->loadPackage();
        }
    }

    private function loadPackage()
    {
        $package = RehabPackage::with('items')->findOrFail($this->packageId);
        
        $this->name = $package->name;
        $this->description = $package->description;
        $this->base_price = $package->base_price;
        $this->discount_type = $package->discount_type;
        $this->discount_value = $package->discount_value;
        $this->is_active = $package->is_active;
        
        // Load items by type
        foreach ($package->items as $item) {
            $itemData = [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'dosage' => $item->dosage,
                'frequency' => $item->frequency,
                'duration' => $item->duration,
                'quantity' => $item->quantity,
                'instructions' => $item->instructions,
                'notes' => $item->notes,
            ];
            
            if ($item->item_type === 'standard_medication') {
                $this->standardMedications[] = $itemData;
            } elseif ($item->item_type === 'custom_medication') {
                $this->customMedications[] = $itemData;
            } elseif ($item->item_type === 'service') {
                $this->services[] = [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'instructions' => $item->instructions,
                    'notes' => $item->notes,
                ];
            } elseif ($item->item_type === 'bed') {
                $this->bed = [
                    'id' => $item->id,
                    'bed_duration_days' => $item->bed_duration_days,
                    'instructions' => $item->instructions,
                    'notes' => $item->notes,
                ];
            }
        }
    }

    public function setItemType($type)
    {
        $this->currentItem = [
            'item_type' => $type,
            'item_name' => '',
            'dosage' => '',
            'frequency' => '',
            'duration' => '',
            'quantity' => '',
            'instructions' => '',
            'notes' => '',
            'bed_duration_days' => null,
        ];
        $this->editingType = $type;
        $this->editingIndex = null;
        
        // Scroll to form
        $this->dispatch('scrollToForm');
    }

    public function addItem()
    {
        $this->validate();
        
        // Check bed limit
        if ($this->currentItem['item_type'] === 'bed' && $this->bed) {
            $this->showAlert('Only one bed entry is allowed per package.', 'error');
            return;
        }
        
        // Add to appropriate collection
        switch ($this->currentItem['item_type']) {
            case 'standard_medication':
                $this->standardMedications[] = $this->currentItem;
                break;
            case 'custom_medication':
                $this->customMedications[] = $this->currentItem;
                break;
            case 'service':
                $this->services[] = [
                    'item_name' => $this->currentItem['item_name'],
                    'instructions' => $this->currentItem['instructions'],
                    'notes' => $this->currentItem['notes'],
                ];
                break;
            case 'bed':
                $this->bed = [
                    'bed_duration_days' => $this->currentItem['bed_duration_days'],
                    'instructions' => $this->currentItem['instructions'],
                    'notes' => $this->currentItem['notes'],
                ];
                break;
        }
        
        // Reset current item
        $this->resetCurrentItem();
        $this->editingType = null;
        
        $this->showAlert('Item added successfully!', 'success');
    }

    public function updateItem()
    {
        $this->validate();
        
        if ($this->editingIndex !== null) {
            // Update existing item
            switch ($this->editingType) {
                case 'standard_medication':
                    $this->standardMedications[$this->editingIndex] = $this->currentItem;
                    break;
                case 'custom_medication':
                    $this->customMedications[$this->editingIndex] = $this->currentItem;
                    break;
                case 'service':
                    $this->services[$this->editingIndex] = [
                        'item_name' => $this->currentItem['item_name'],
                        'instructions' => $this->currentItem['instructions'],
                        'notes' => $this->currentItem['notes'],
                    ];
                    break;
            }
        }
        
        $this->resetCurrentItem();
        $this->editingType = null;
        $this->editingIndex = null;
        
        $this->showAlert('Item updated successfully!', 'success');
    }

    public function editItem($type, $index)
    {
        $this->editingType = $type;
        $this->editingIndex = $index;
        
        switch ($type) {
            case 'standard_medication':
            case 'custom_medication':
                $this->currentItem = $this->{$type}[$index];
                break;
            case 'service':
                $this->currentItem = [
                    'item_type' => 'service',
                    'item_name' => $this->services[$index]['item_name'],
                    'instructions' => $this->services[$index]['instructions'] ?? '',
                    'notes' => $this->services[$index]['notes'] ?? '',
                    'dosage' => '',
                    'frequency' => '',
                    'duration' => '',
                    'quantity' => '',
                    'bed_duration_days' => null,
                ];
                break;
        }
        
        $this->dispatch('scrollToForm');
    }

    public function editBed()
    {
        if ($this->bed) {
            $this->currentItem = [
                'item_type' => 'bed',
                'item_name' => 'Bed Accommodation',
                'bed_duration_days' => $this->bed['bed_duration_days'],
                'instructions' => $this->bed['instructions'] ?? '',
                'notes' => $this->bed['notes'] ?? '',
                'dosage' => '',
                'frequency' => '',
                'duration' => '',
                'quantity' => '',
            ];
            $this->editingType = 'bed';
            $this->dispatch('scrollToForm');
        }
    }

    public function removeItem($type, $index)
    {
        switch ($type) {
            case 'standard_medication':
                unset($this->standardMedications[$index]);
                $this->standardMedications = array_values($this->standardMedications);
                break;
            case 'custom_medication':
                unset($this->customMedications[$index]);
                $this->customMedications = array_values($this->customMedications);
                break;
            case 'service':
                unset($this->services[$index]);
                $this->services = array_values($this->services);
                break;
        }
        
        $this->showAlert('Item removed.', 'info');
    }

    public function removeBed()
    {
        $this->bed = null;
        $this->showAlert('Bed accommodation removed.', 'info');
    }

    public function cancelEdit()
    {
        $this->resetCurrentItem();
        $this->editingType = null;
        $this->editingIndex = null;
    }

    private function resetCurrentItem()
    {
        $this->currentItem = [
            'item_type' => '',
            'item_name' => '',
            'dosage' => '',
            'frequency' => '',
            'duration' => '',
            'quantity' => '',
            'instructions' => '',
            'notes' => '',
            'bed_duration_days' => null,
        ];
        $this->resetErrorBag();
    }

    public function calculateFinalPrice()
    {
        $finalPrice = (float)$this->base_price;
        
        if ($this->discount_type && $this->discount_value) {
            if ($this->discount_type === 'fixed') {
                $finalPrice = $this->base_price - $this->discount_value;
            } elseif ($this->discount_type === 'percentage') {
                $finalPrice = $this->base_price - ($this->base_price * $this->discount_value / 100);
            }
        }
        
        return max(0, $finalPrice);
    }

    public function save()
    {
        // Custom validation
        $this->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);
        
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
                
                // Delete existing items
                $package->items()->delete();
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
            }
            
            // Save standard medications
            foreach ($this->standardMedications as $med) {
                $package->items()->create([
                    'item_type' => 'standard_medication',
                    'item_name' => $med['item_name'],
                    'dosage' => $med['dosage'] ?? null,
                    'frequency' => $med['frequency'] ?? null,
                    'duration' => $med['duration'] ?? null,
                    'quantity' => $med['quantity'] ?? null,
                    'instructions' => $med['instructions'] ?? null,
                    'notes' => $med['notes'] ?? null,
                ]);
            }
            
            // Save custom medications
            foreach ($this->customMedications as $med) {
                $package->items()->create([
                    'item_type' => 'custom_medication',
                    'item_name' => $med['item_name'],
                    'dosage' => $med['dosage'] ?? null,
                    'frequency' => $med['frequency'] ?? null,
                    'duration' => $med['duration'] ?? null,
                    'quantity' => $med['quantity'] ?? null,
                    'instructions' => $med['instructions'] ?? null,
                    'notes' => $med['notes'] ?? null,
                ]);
            }
            
            // Save services
            foreach ($this->services as $service) {
                $package->items()->create([
                    'item_type' => 'service',
                    'item_name' => $service['item_name'],
                    'instructions' => $service['instructions'] ?? null,
                    'notes' => $service['notes'] ?? null,
                ]);
            }
            
            // Save bed
            if ($this->bed) {
                $package->items()->create([
                    'item_type' => 'bed',
                    'item_name' => 'Bed Accommodation',
                    'bed_duration_days' => $this->bed['bed_duration_days'],
                    'instructions' => $this->bed['instructions'] ?? null,
                    'notes' => $this->bed['notes'] ?? null,
                ]);
            }
        });
        
        $this->showAlert(
            $this->packageId ? 'Package updated successfully!' : 'Package created successfully!', 
            'success'
        );
        
        // Redirect after short delay
        $this->dispatch('redirectAfterDelay', url: route('rehab.packages.index'));
    }

    public function saveAndContinue()
    {
        $this->save();
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'description', 'base_price', 'discount_type', 
            'discount_value', 'standardMedications', 'customMedications', 
            'services', 'bed', 'editingType', 'editingIndex'
        ]);
        $this->resetCurrentItem();
        $this->is_active = true;
        $this->base_price = 0;
    }

    private function showAlert($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.forms.rehab-package-form', [
            'frequencies' => $this->frequencies,
            'finalPrice' => $this->calculateFinalPrice(),
        ]);
    }
}, 