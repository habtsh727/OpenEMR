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
    
    // Frequencies for dropdown
    public $frequencies = [];
    
    // UI State
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';
    public $editingId = null;
    public $editingType = null;
    public $editingIndex = null;
    
    // Search for standard medications (assuming we have a medications table)
    public $medicationSearch = '';
    public $showMedicationDropdown = false;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'base_price' => 'required|numeric|min:0',
        'discount_type' => 'nullable|in:fixed,percentage',
        'discount_value' => 'nullable|numeric|min:0',
        'is_active' => 'boolean',
        
        // Standard Medications
        'standardMedications.*.item_name' => 'required|string|max:255',
        'standardMedications.*.dosage' => 'required|string',
        'standardMedications.*.frequency_id' => 'required|exists:frequencies,id',
        'standardMedications.*.duration' => 'required|string',
        'standardMedications.*.notes' => 'nullable|string',
        
        // Custom Medications
        'customMedications.*.item_name' => 'required|string|max:255',
        'customMedications.*.dosage' => 'required|string',
        'customMedications.*.frequency_id' => 'required|exists:frequencies,id',
        'customMedications.*.duration' => 'required|string',
        'customMedications.*.notes' => 'nullable|string',
        
        // Services
        'services.*.item_name' => 'required|string|max:255',
        'services.*.notes' => 'nullable|string',
        
        // Bed
        'bed.bed_duration_days' => 'required_if:bed,!=,null|integer|min:1|nullable',
        'bed.notes' => 'nullable|string',
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
        $package = RehabPackage::with('items.frequency')->findOrFail($this->packageId);
        
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
                'frequency_id' => $item->frequency_id,
                'duration' => $item->duration,
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
                    'notes' => $item->notes,
                ];
            } elseif ($item->item_type === 'bed') {
                $this->bed = [
                    'id' => $item->id,
                    'bed_duration_days' => $item->bed_duration_days,
                    'notes' => $item->notes,
                ];
            }
        }
    }

    public function addStandardMedication()
    {
        $this->standardMedications[] = [
            'item_name' => '',
            'dosage' => '',
            'frequency_id' => null,
            'duration' => '',
            'notes' => '',
        ];
    }

    public function addCustomMedication()
    {
        $this->customMedications[] = [
            'item_name' => '',
            'dosage' => '',
            'frequency_id' => null,
            'duration' => '',
            'notes' => '',
        ];
    }

    public function addService()
    {
        $this->services[] = [
            'item_name' => '',
            'notes' => '',
        ];
    }

    public function addBed()
    {
        if ($this->bed) {
            $this->showAlert('Only one bed entry is allowed per package.', 'error');
            return;
        }
        
        $this->bed = [
            'bed_duration_days' => null,
            'notes' => '',
        ];
    }

    public function removeStandardMedication($index)
    {
        unset($this->standardMedications[$index]);
        $this->standardMedications = array_values($this->standardMedications);
    }

    public function removeCustomMedication($index)
    {
        unset($this->customMedications[$index]);
        $this->customMedications = array_values($this->customMedications);
    }

    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
    }

    public function removeBed()
    {
        $this->bed = null;
    }

    public function editStandardMedication($index)
    {
        $this->editingType = 'standard';
        $this->editingIndex = $index;
        // Scroll to form
        $this->dispatch('scrollToForm');
    }

    public function editCustomMedication($index)
    {
        $this->editingType = 'custom';
        $this->editingIndex = $index;
        $this->dispatch('scrollToForm');
    }

    public function editService($index)
    {
        $this->editingType = 'service';
        $this->editingIndex = $index;
        $this->dispatch('scrollToForm');
    }

    public function editBed()
    {
        $this->editingType = 'bed';
        $this->editingIndex = null;
        $this->dispatch('scrollToForm');
    }

    public function cancelEdit()
    {
        $this->editingType = null;
        $this->editingIndex = null;
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
        // Custom validation for bed
        if ($this->bed && !isset($this->bed['bed_duration_days'])) {
            $this->addError('bed.bed_duration_days', 'Bed duration days is required.');
            return;
        }

        $this->validate();
        
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
                
                // Delete existing items (will be recreated)
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
                    'dosage' => $med['dosage'],
                    'frequency_id' => $med['frequency_id'],
                    'duration' => $med['duration'],
                    'notes' => $med['notes'] ?? null,
                ]);
            }
            
            // Save custom medications
            foreach ($this->customMedications as $med) {
                $package->items()->create([
                    'item_type' => 'custom_medication',
                    'item_name' => $med['item_name'],
                    'dosage' => $med['dosage'],
                    'frequency_id' => $med['frequency_id'],
                    'duration' => $med['duration'],
                    'notes' => $med['notes'] ?? null,
                ]);
            }
            
            // Save services
            foreach ($this->services as $service) {
                $package->items()->create([
                    'item_type' => 'service',
                    'item_name' => $service['item_name'],
                    'notes' => $service['notes'] ?? null,
                ]);
            }
            
            // Save bed
            if ($this->bed) {
                $package->items()->create([
                    'item_type' => 'bed',
                    'item_name' => 'Bed Accommodation',
                    'bed_duration_days' => $this->bed['bed_duration_days'],
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
        $this->is_active = true;
        $this->base_price = 0;
        $this->resetErrorBag();
    }

    public function selectMedication($medicationId, $medicationName)
    {
        // This would be implemented if you have a medications table
        // For now, just set the name
        $this->standardMedications[count($this->standardMedications) - 1]['item_name'] = $medicationName;
        $this->showMedicationDropdown = false;
        $this->medicationSearch = '';
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
}
   