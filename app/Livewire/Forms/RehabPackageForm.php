<?php

namespace App\Livewire\Forms;

use App\Models\RehabPackage;
use App\Models\RehabPackageItem;
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
    
    // UI State
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';
    public $editingId = null;
    public $editingType = null;
    public $editingIndex = null;
    public $showItemForm = false;
    public $selectedItemType = '';
    
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
    
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ];
        
        // Add current item validation rules only when form is shown
        if ($this->showItemForm && $this->selectedItemType) {
            $rules['currentItem.item_name'] = 'required|string|max:255';
            
            if (in_array($this->selectedItemType, ['standard_medication', 'custom_medication'])) {
                $rules['currentItem.dosage'] = 'required|string';
                $rules['currentItem.frequency'] = 'required|string';
                $rules['currentItem.duration'] = 'required|string';
                $rules['currentItem.quantity'] = 'required|string';
            }
            
            if ($this->selectedItemType === 'bed') {
                $rules['currentItem.bed_duration_days'] = 'required|integer|min:1';
            }
        }
        
        return $rules;
    }

    public function mount($id = null)
    {
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

    public function showAddItemForm()
    {
        $this->showItemForm = true;
        $this->selectedItemType = '';
        $this->resetCurrentItem();
        $this->dispatch('scrollToForm');
    }

    public function selectItemType()
    {
        if (!$this->selectedItemType) {
            $this->showAlert('Please select an item type.', 'error');
            return;
        }

        // Check bed limit
        if ($this->selectedItemType === 'bed' && $this->bed) {
            $this->showAlert('Only one bed entry is allowed per package.', 'error');
            $this->selectedItemType = '';
            return;
        }

        $this->currentItem['item_type'] = $this->selectedItemType;
    }

    public function saveItem()
    {
        $this->validate();
        
        // Add to appropriate collection
        switch ($this->selectedItemType) {
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
        
        $this->cancelItemForm();
        $this->showAlert('Item added successfully!', 'success');
    }

    public function editItem($type, $index)
    {
        $this->showItemForm = true;
        $this->selectedItemType = $type;
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
            $this->showItemForm = true;
            $this->selectedItemType = 'bed';
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
            $this->dispatch('scrollToForm');
        }
    }

    public function updateItem()
    {
        $this->validate();
        
        if ($this->editingIndex !== null) {
            // Update existing item
            switch ($this->selectedItemType) {
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
        } elseif ($this->selectedItemType === 'bed') {
            $this->bed = [
                'bed_duration_days' => $this->currentItem['bed_duration_days'],
                'instructions' => $this->currentItem['instructions'],
                'notes' => $this->currentItem['notes'],
            ];
        }
        
        $this->cancelItemForm();
        $this->showAlert('Item updated successfully!', 'success');
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

    public function cancelItemForm()
    {
        $this->showItemForm = false;
        $this->selectedItemType = '';
        $this->editingIndex = null;
        $this->resetCurrentItem();
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
        // Basic validation
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
        // $this->dispatch('redirectAfterDelay', url: route('rehab.packages.index'));
        $this->redirect(route('rehab.packages.index'), navigate: true);
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'description', 'base_price', 'discount_type', 
            'discount_value', 'standardMedications', 'customMedications', 
            'services', 'bed', 'editingIndex'
        ]);
        $this->cancelItemForm();
        $this->is_active = true;
        $this->base_price = 0;
    }

    private function showAlert($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        // Auto close after 3 seconds
        $this->dispatch('closeAlertAfterDelay');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.forms.rehab-package-form', [
            'finalPrice' => $this->calculateFinalPrice(),
        ]);
    }
}
