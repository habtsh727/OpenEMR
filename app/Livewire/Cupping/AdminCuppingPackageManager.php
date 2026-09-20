<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use App\Models\CuppingPackage;
use App\Models\CuppingPackageTreatment;
use App\Models\CuppingPackageMaterial;
use App\Models\CuppingType;
use App\Models\CuppingLocation;
use App\Models\PharmacyItem;
use Illuminate\Support\Facades\DB;

class AdminCuppingPackageManager extends Component
{
    public $packages = [];

    // Form properties
    public $showForm = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $discount_type = '';
    public $discount_value = '';

    // Treatments
    public $treatments = [];
    public $treatmentTypes = [];
    public $treatmentLocations = [];

    // Materials
    public $materials = [];
    public $pharmacyItems = [];

    // Calculated
    public $basePrice = 0;
    public $totalPrice = 0;

    // Alert
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    public function mount()
    {
        $this->loadPackages();
        $this->loadTreatmentTypes();
        $this->loadTreatmentLocations();
        $this->loadPharmacyItems();
        $this->addTreatment();
        $this->addMaterial();
    }

    public function loadPackages()
    {
        $this->packages = CuppingPackage::with(['treatments', 'materials'])
            ->orderBy('name')
            ->get();
    }

    public function loadTreatmentTypes()
    {
        $this->treatmentTypes = CuppingType::where('status', true)->get();
    }

    public function loadTreatmentLocations()
    {
        $this->treatmentLocations = CuppingLocation::where('status', true)->get();
    }

    public function loadPharmacyItems()
    {
        $this->pharmacyItems = PharmacyItem::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    public function addTreatment()
    {
        $this->treatments[] = [
            'id' => uniqid(),
            'cupping_type_id' => '',
            'cupping_location_id' => '',
            'treatment_price' => 0,
        ];
        $this->calculatePrices();
    }

    public function removeTreatment($index)
    {
        unset($this->treatments[$index]);
        $this->treatments = array_values($this->treatments);
        $this->calculatePrices();
    }

    public function addMaterial()
    {
        $this->materials[] = [
            'id' => uniqid(),
            'pharmacy_item_id' => '',
            'quantity_required' => 1,
            'unit_price' => 0,
            'total' => 0,
        ];
        $this->calculatePrices();
    }

    public function removeMaterial($index)
    {
        unset($this->materials[$index]);
        $this->materials = array_values($this->materials);
        $this->calculatePrices();
    }

    public function updatedMaterials($value, $key)
    {
        $this->calculatePrices();
    }

    public function updatedTreatments($value, $key)
    {
        $this->calculatePrices();
    }

    public function updateMaterialPrice($index)
    {
        $material = $this->materials[$index];
        if (!empty($material['pharmacy_item_id'])) {
            $item = PharmacyItem::find($material['pharmacy_item_id']);
            if ($item) {
                // Get latest batch selling price
                $batch = \App\Models\PharmacyBatch::where('medicine_id', $item->id)
                    ->where('quantity', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->orderBy('expiry_date')
                    ->first();

                $price = $batch ? $batch->selling_price : 0;
                $this->materials[$index]['unit_price'] = $price;
                $this->materials[$index]['total'] = $price * ($material['quantity_required'] ?? 1);
                $this->calculatePrices();
            }
        }
    }

    public function calculatePrices()
    {
        // Calculate treatment total
        $treatmentTotal = 0;
        foreach ($this->treatments as $treatment) {
            $treatmentTotal += floatval($treatment['treatment_price'] ?? 0);
        }

        // Calculate material total
        $materialTotal = 0;
        foreach ($this->materials as $material) {
            $materialTotal += floatval($material['total'] ?? 0);
        }

        $this->basePrice = $treatmentTotal + $materialTotal;

        // Apply discount
        $discountValue = floatval($this->discount_value ?? 0);
        if ($this->discount_type === 'percentage' && $discountValue > 0) {
            $this->totalPrice = $this->basePrice - ($this->basePrice * $discountValue / 100);
        } elseif ($this->discount_type === 'fixed' && $discountValue > 0) {
            $this->totalPrice = max(0, $this->basePrice - $discountValue);
        } else {
            $this->totalPrice = $this->basePrice;
        }
    }

    public function editPackage($id)
    {
        $package = CuppingPackage::with(['treatments', 'materials'])->find($id);
        if ($package) {
            $this->editingId = $id;
            $this->name = $package->name;
            $this->description = $package->description;
            $this->discount_type = $package->discount_type;
            $this->discount_value = $package->discount_value;

            // Load treatments
            $this->treatments = [];
            foreach ($package->treatments as $treatment) {
                $this->treatments[] = [
                    'id' => $treatment->id,
                    'cupping_type_id' => $treatment->cupping_type_id,
                    'cupping_location_id' => $treatment->cupping_location_id,
                    'treatment_price' => $treatment->treatment_price,
                ];
            }

            // Load materials
            $this->materials = [];
            foreach ($package->materials as $material) {
                $this->materials[] = [
                    'id' => $material->id,
                    'pharmacy_item_id' => $material->pharmacy_item_id,
                    'quantity_required' => $material->quantity_required,
                    'unit_price' => $material->unit_price_snapshot,
                    'total' => $material->total_material_cost,
                ];
            }

            $this->calculatePrices();
            $this->showForm = true;
        }
    }

    public function savePackage()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'treatments' => 'required|array|min:1',
            'treatments.*.cupping_type_id' => 'required|exists:cupping_types,id',
            'treatments.*.cupping_location_id' => 'required|exists:cupping_locations,id',
            'treatments.*.treatment_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            if ($this->editingId) {
                $package = CuppingPackage::find($this->editingId);
                $package->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'base_price' => $this->basePrice,
                    'discount_type' => $this->discount_type ?: null,
                    'discount_value' => $this->discount_value ?: null,
                    'total_price' => $this->totalPrice,
                ]);

                // Delete old treatments and materials
                $package->treatments()->delete();
                $package->materials()->delete();
            } else {
                $package = CuppingPackage::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'base_price' => $this->basePrice,
                    'discount_type' => $this->discount_type ?: null,
                    'discount_value' => $this->discount_value ?: null,
                    'total_price' => $this->totalPrice,
                    'is_active' => true,
                    'created_by' => auth()->id(),
                ]);
            }

            // Save treatments
            foreach ($this->treatments as $treatment) {
                CuppingPackageTreatment::create([
                    'package_id' => $package->id,
                    'cupping_type_id' => $treatment['cupping_type_id'],
                    'cupping_location_id' => $treatment['cupping_location_id'],
                    'treatment_price' => $treatment['treatment_price'],
                ]);
            }

            // Save materials
            foreach ($this->materials as $material) {
                if (!empty($material['pharmacy_item_id'])) {
                    CuppingPackageMaterial::create([
                        'package_id' => $package->id,
                        'pharmacy_item_id' => $material['pharmacy_item_id'],
                        'quantity_required' => $material['quantity_required'] ?? 1,
                        'unit_price_snapshot' => $material['unit_price'] ?? 0,
                        'total_material_cost' => $material['total'] ?? 0,
                    ]);
                }
            }

            DB::commit();

            $this->showAlertMessage(
                $this->editingId ? 'Package updated successfully!' : 'Package created successfully!',
                'success'
            );

            $this->resetForm();
            $this->loadPackages();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Error saving package: ' . $e->getMessage(), 'error');
        }
    }

    public function togglePackageStatus($id)
    {
        $package = CuppingPackage::find($id);
        if ($package) {
            $package->update(['is_active' => !$package->is_active]);
            $this->loadPackages();
            $this->showAlertMessage(
                $package->is_active ? 'Package activated!' : 'Package deactivated!',
                'success'
            );
        }
    }

    public function deletePackage($id)
    {
        $package = CuppingPackage::find($id);
        if ($package) {
            $package->delete();
            $this->loadPackages();
            $this->showAlertMessage('Package deleted successfully!', 'success');
        }
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->discount_type = '';
        $this->discount_value = '';
        $this->treatments = [];
        $this->materials = [];
        $this->basePrice = 0;
        $this->totalPrice = 0;
        $this->addTreatment();
        $this->addMaterial();
    }

    public function showAlertMessage($message, $type = 'success')
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
        return view('livewire.cupping.admin-cupping-package-manager');
    }
}
