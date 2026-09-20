<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CuppingPackage;
use App\Models\CuppingPackageTreatment;
use App\Models\CuppingPackageMaterial;
use App\Models\CuppingType;
use App\Models\CuppingLocation;
use App\Models\PharmacyItem;
use App\Models\PharmacyBatch;
use Illuminate\Support\Facades\DB;

class CuppingPackageSeeder extends Seeder
{
    public function run()
    {
        // First, ensure we have cupping types and locations
        $this->ensureCuppingTypesAndLocations();

        // Ensure we have pharmacy items for materials
        $this->ensurePharmacyItems();

        // Create 5 packages
        $this->createBasicCuppingPackage();
        $this->createStandardCuppingPackage();
        $this->createPremiumCuppingPackage();
        $this->createFullBodyCuppingPackage();
        $this->createTherapeuticCuppingPackage();

        $this->command->info('5 Cupping packages created successfully!');
    }

    private function ensureCuppingTypesAndLocations()
    {
        // Create cupping types if not exist
        $types = [
            ['name' => 'Dry Cupping', 'description' => 'Traditional suction cup therapy', 'status' => true],
            ['name' => 'Wet Cupping', 'description' => 'Cupping with minor incisions', 'status' => true],
            ['name' => 'Flash Cupping', 'description' => 'Quick suction and release', 'status' => true],
            ['name' => 'Massage Cupping', 'description' => 'Cupping with gliding motion', 'status' => true],
        ];

        foreach ($types as $type) {
            CuppingType::firstOrCreate(['name' => $type['name']], $type);
        }

        // Create cupping locations if not exist
        $locations = [
            ['name' => 'Back', 'description' => 'Upper and lower back area', 'status' => true],
            ['name' => 'Shoulders', 'description' => 'Shoulder and neck area', 'status' => true],
            ['name' => 'Legs', 'description' => 'Thigh and calf area', 'status' => true],
            ['name' => 'Arms', 'description' => 'Upper and lower arms', 'status' => true],
            ['name' => 'Abdomen', 'description' => 'Stomach area', 'status' => true],
            ['name' => 'Chest', 'description' => 'Chest area', 'status' => true],
            ['name' => 'Head', 'description' => 'Scalp and forehead', 'status' => true],
            ['name' => 'Full Body', 'description' => 'Complete body treatment', 'status' => true],
        ];

        foreach ($locations as $location) {
            CuppingLocation::firstOrCreate(['name' => $location['name']], $location);
        }
    }

    private function ensurePharmacyItems()
    {
        // Create pharmacy items for cupping materials if they don't exist
        $items = [
            ['code' => 'CUP-GLASS-001', 'name' => 'Glass Cups Set', 'generic_name' => 'Cupping Glass Cups', 'category_id' => 1, 'unit_id' => 1, 'route_id' => 1, 'strength' => 'Set of 6', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-SIL-001', 'name' => 'Silicone Cups Set', 'generic_name' => 'Cupping Silicone Cups', 'category_id' => 1, 'unit_id' => 1, 'route_id' => 1, 'strength' => 'Set of 4', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-PLAS-001', 'name' => 'Plastic Cups Set', 'generic_name' => 'Cupping Plastic Cups', 'category_id' => 1, 'unit_id' => 1, 'route_id' => 1, 'strength' => 'Set of 5', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-BAM-001', 'name' => 'Bamboo Cups Set', 'generic_name' => 'Cupping Bamboo Cups', 'category_id' => 1, 'unit_id' => 1, 'route_id' => 1, 'strength' => 'Set of 4', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-PUMP-001', 'name' => 'Vacuum Pump', 'generic_name' => 'Cupping Vacuum Pump', 'category_id' => 1, 'unit_id' => 1, 'route_id' => 1, 'strength' => 'Manual Pump', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-OIL-001', 'name' => 'Cupping Oil', 'generic_name' => 'Massage Oil for Cupping', 'category_id' => 1, 'unit_id' => 2, 'route_id' => 1, 'strength' => '100ml', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-ALC-001', 'name' => 'Alcohol Swabs', 'generic_name' => 'Sterile Alcohol Swabs', 'category_id' => 1, 'unit_id' => 3, 'route_id' => 1, 'strength' => '100pcs', 'is_prescription_required' => false, 'is_active' => true],
            ['code' => 'CUP-GLOVE-001', 'name' => 'Medical Gloves', 'generic_name' => 'Disposable Medical Gloves', 'category_id' => 1, 'unit_id' => 3, 'route_id' => 1, 'strength' => 'Pair', 'is_prescription_required' => false, 'is_active' => true],
        ];

        foreach ($items as $item) {
            PharmacyItem::firstOrCreate(['code' => $item['code']], $item);
        }

        // Create batches for these items with stock
        $pharmacyItems = PharmacyItem::whereIn('code', [
            'CUP-GLASS-001', 'CUP-SIL-001', 'CUP-PLAS-001', 'CUP-BAM-001',
            'CUP-PUMP-001', 'CUP-OIL-001', 'CUP-ALC-001', 'CUP-GLOVE-001'
        ])->get();

        foreach ($pharmacyItems as $item) {
            // Check if batch already exists
            $existingBatch = PharmacyBatch::where('medicine_id', $item->id)->first();
            if (!$existingBatch) {
                PharmacyBatch::create([
                    'medicine_id' => $item->id,
                    'batch_number' => 'BATCH-' . strtoupper(substr($item->code, -3)) . '-001',
                    'expiry_date' => now()->addYears(2),
                    'quantity' => 100,
                    'purchase_price' => 50,
                    'selling_price' => $item->name === 'Glass Cups Set' ? 150 :
                                     ($item->name === 'Silicone Cups Set' ? 120 :
                                     ($item->name === 'Plastic Cups Set' ? 80 :
                                     ($item->name === 'Bamboo Cups Set' ? 100 :
                                     ($item->name === 'Vacuum Pump' ? 200 :
                                     ($item->name === 'Cupping Oil' ? 30 :
                                     ($item->name === 'Alcohol Swabs' ? 15 : 10)))))),
                    'is_active' => true,
                ]);
            }
        }
    }

    private function createBasicCuppingPackage()
    {
        // Get IDs
        $dryCuppingId = CuppingType::where('name', 'Dry Cupping')->first()->id;
        $backLocationId = CuppingLocation::where('name', 'Back')->first()->id;
        $glassCupsId = PharmacyItem::where('code', 'CUP-GLASS-001')->first()->id;
        $oilId = PharmacyItem::where('code', 'CUP-OIL-001')->first()->id;

        $package = CuppingPackage::create([
            'name' => 'Basic Cupping Package',
            'description' => 'Essential cupping treatment for back pain relief. Includes dry cupping on back area.',
            'base_price' => 0,
            'discount_type' => null,
            'discount_value' => null,
            'total_price' => 0,
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Add treatment
        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $dryCuppingId,
            'cupping_location_id' => $backLocationId,
            'treatment_price' => 250,
        ]);

        // Add materials
        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $glassCupsId,
            'quantity_required' => 4,
            'unit_price_snapshot' => 150,
            'total_material_cost' => 600,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $oilId,
            'quantity_required' => 10, // ml
            'unit_price_snapshot' => 30,
            'total_material_cost' => 300,
        ]);

        // Update package prices
        $treatmentTotal = 250;
        $materialTotal = 600 + 300;
        $basePrice = $treatmentTotal + $materialTotal;

        $package->update([
            'base_price' => $basePrice,
            'total_price' => $basePrice,
        ]);
    }

    private function createStandardCuppingPackage()
    {
        $wetCuppingId = CuppingType::where('name', 'Wet Cupping')->first()->id;
        $backLocationId = CuppingLocation::where('name', 'Back')->first()->id;
        $shouldersLocationId = CuppingLocation::where('name', 'Shoulders')->first()->id;
        $siliconeCupsId = PharmacyItem::where('code', 'CUP-SIL-001')->first()->id;
        $oilId = PharmacyItem::where('code', 'CUP-OIL-001')->first()->id;
        $alcoholId = PharmacyItem::where('code', 'CUP-ALC-001')->first()->id;
        $glovesId = PharmacyItem::where('code', 'CUP-GLOVE-001')->first()->id;

        $package = CuppingPackage::create([
            'name' => 'Standard Cupping Package',
            'description' => 'Complete cupping therapy for back and shoulders. Includes wet cupping with sterilized equipment.',
            'base_price' => 0,
            'discount_type' => null,
            'discount_value' => null,
            'total_price' => 0,
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Add treatments
        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $wetCuppingId,
            'cupping_location_id' => $backLocationId,
            'treatment_price' => 350,
        ]);

        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $wetCuppingId,
            'cupping_location_id' => $shouldersLocationId,
            'treatment_price' => 250,
        ]);

        // Add materials
        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $siliconeCupsId,
            'quantity_required' => 6,
            'unit_price_snapshot' => 120,
            'total_material_cost' => 720,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $oilId,
            'quantity_required' => 15,
            'unit_price_snapshot' => 30,
            'total_material_cost' => 450,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $alcoholId,
            'quantity_required' => 10,
            'unit_price_snapshot' => 15,
            'total_material_cost' => 150,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $glovesId,
            'quantity_required' => 2,
            'unit_price_snapshot' => 10,
            'total_material_cost' => 20,
        ]);

        // Update package prices
        $treatmentTotal = 350 + 250;
        $materialTotal = 720 + 450 + 150 + 20;
        $basePrice = $treatmentTotal + $materialTotal;

        $package->update([
            'base_price' => $basePrice,
            'total_price' => $basePrice,
        ]);
    }

    private function createPremiumCuppingPackage()
    {
        $dryCuppingId = CuppingType::where('name', 'Dry Cupping')->first()->id;
        $wetCuppingId = CuppingType::where('name', 'Wet Cupping')->first()->id;
        $massageCuppingId = CuppingType::where('name', 'Massage Cupping')->first()->id;
        $fullBodyId = CuppingLocation::where('name', 'Full Body')->first()->id;
        $glassCupsId = PharmacyItem::where('code', 'CUP-GLASS-001')->first()->id;
        $bambooCupsId = PharmacyItem::where('code', 'CUP-BAM-001')->first()->id;
        $pumpId = PharmacyItem::where('code', 'CUP-PUMP-001')->first()->id;
        $oilId = PharmacyItem::where('code', 'CUP-OIL-001')->first()->id;
        $alcoholId = PharmacyItem::where('code', 'CUP-ALC-001')->first()->id;
        $glovesId = PharmacyItem::where('code', 'CUP-GLOVE-001')->first()->id;

        $package = CuppingPackage::create([
            'name' => 'Premium Cupping Package',
            'description' => 'Premium full body cupping with combination of dry, wet, and massage cupping techniques.',
            'base_price' => 0,
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'total_price' => 0,
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Add treatments
        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $dryCuppingId,
            'cupping_location_id' => $fullBodyId,
            'treatment_price' => 400,
        ]);

        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $wetCuppingId,
            'cupping_location_id' => $fullBodyId,
            'treatment_price' => 500,
        ]);

        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $massageCuppingId,
            'cupping_location_id' => $fullBodyId,
            'treatment_price' => 300,
        ]);

        // Add materials
        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $glassCupsId,
            'quantity_required' => 8,
            'unit_price_snapshot' => 150,
            'total_material_cost' => 1200,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $bambooCupsId,
            'quantity_required' => 6,
            'unit_price_snapshot' => 100,
            'total_material_cost' => 600,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $pumpId,
            'quantity_required' => 1,
            'unit_price_snapshot' => 200,
            'total_material_cost' => 200,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $oilId,
            'quantity_required' => 30,
            'unit_price_snapshot' => 30,
            'total_material_cost' => 900,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $alcoholId,
            'quantity_required' => 20,
            'unit_price_snapshot' => 15,
            'total_material_cost' => 300,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $glovesId,
            'quantity_required' => 4,
            'unit_price_snapshot' => 10,
            'total_material_cost' => 40,
        ]);

        // Update package prices
        $treatmentTotal = 400 + 500 + 300;
        $materialTotal = 1200 + 600 + 200 + 900 + 300 + 40;
        $basePrice = $treatmentTotal + $materialTotal;
        $totalPrice = $basePrice - ($basePrice * 10 / 100);

        $package->update([
            'base_price' => $basePrice,
            'total_price' => $totalPrice,
        ]);
    }

    private function createFullBodyCuppingPackage()
    {
        $dryCuppingId = CuppingType::where('name', 'Dry Cupping')->first()->id;
        $fullBodyId = CuppingLocation::where('name', 'Full Body')->first()->id;
        $plasticCupsId = PharmacyItem::where('code', 'CUP-PLAS-001')->first()->id;
        $oilId = PharmacyItem::where('code', 'CUP-OIL-001')->first()->id;

        $package = CuppingPackage::create([
            'name' => 'Full Body Relaxation Package',
            'description' => 'Full body dry cupping for complete relaxation and stress relief.',
            'base_price' => 0,
            'discount_type' => 'fixed',
            'discount_value' => 200,
            'total_price' => 0,
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Add treatment
        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $dryCuppingId,
            'cupping_location_id' => $fullBodyId,
            'treatment_price' => 600,
        ]);

        // Add materials
        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $plasticCupsId,
            'quantity_required' => 10,
            'unit_price_snapshot' => 80,
            'total_material_cost' => 800,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $oilId,
            'quantity_required' => 25,
            'unit_price_snapshot' => 30,
            'total_material_cost' => 750,
        ]);

        // Update package prices
        $treatmentTotal = 600;
        $materialTotal = 800 + 750;
        $basePrice = $treatmentTotal + $materialTotal;
        $totalPrice = $basePrice - 200;

        $package->update([
            'base_price' => $basePrice,
            'total_price' => $totalPrice,
        ]);
    }

    private function createTherapeuticCuppingPackage()
    {
        $massageCuppingId = CuppingType::where('name', 'Massage Cupping')->first()->id;
        $flashCuppingId = CuppingType::where('name', 'Flash Cupping')->first()->id;
        $backLocationId = CuppingLocation::where('name', 'Back')->first()->id;
        $shouldersLocationId = CuppingLocation::where('name', 'Shoulders')->first()->id;
        $legsLocationId = CuppingLocation::where('name', 'Legs')->first()->id;
        $siliconeCupsId = PharmacyItem::where('code', 'CUP-SIL-001')->first()->id;
        $oilId = PharmacyItem::where('code', 'CUP-OIL-001')->first()->id;
        $glovesId = PharmacyItem::where('code', 'CUP-GLOVE-001')->first()->id;

        $package = CuppingPackage::create([
            'name' => 'Therapeutic Cupping Package',
            'description' => 'Targeted therapeutic cupping for specific pain areas using massage and flash techniques.',
            'base_price' => 0,
            'discount_type' => null,
            'discount_value' => null,
            'total_price' => 0,
            'is_active' => true,
            'created_by' => 1,
        ]);

        // Add treatments
        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $massageCuppingId,
            'cupping_location_id' => $backLocationId,
            'treatment_price' => 300,
        ]);

        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $massageCuppingId,
            'cupping_location_id' => $shouldersLocationId,
            'treatment_price' => 250,
        ]);

        CuppingPackageTreatment::create([
            'package_id' => $package->id,
            'cupping_type_id' => $flashCuppingId,
            'cupping_location_id' => $legsLocationId,
            'treatment_price' => 200,
        ]);

        // Add materials
        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $siliconeCupsId,
            'quantity_required' => 8,
            'unit_price_snapshot' => 120,
            'total_material_cost' => 960,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $oilId,
            'quantity_required' => 20,
            'unit_price_snapshot' => 30,
            'total_material_cost' => 600,
        ]);

        CuppingPackageMaterial::create([
            'package_id' => $package->id,
            'pharmacy_item_id' => $glovesId,
            'quantity_required' => 3,
            'unit_price_snapshot' => 10,
            'total_material_cost' => 30,
        ]);

        // Update package prices
        $treatmentTotal = 300 + 250 + 200;
        $materialTotal = 960 + 600 + 30;
        $basePrice = $treatmentTotal + $materialTotal;

        $package->update([
            'base_price' => $basePrice,
            'total_price' => $basePrice,
        ]);
    }
}
