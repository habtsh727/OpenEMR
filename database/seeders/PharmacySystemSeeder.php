<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PharmacySystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing data
        DB::table('pharmacy_batches')->truncate();
        DB::table('pharmacy_items')->truncate();
        DB::table('pharmacy_categories')->truncate();
        DB::table('pharmacy_units')->truncate();
        DB::table('pharmacy_routes')->truncate();
        DB::table('pharmacy_frequencies')->truncate();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Seed Categories
        $categories = [
            ['code' => 'ANTIB', 'name' => 'Antibiotics', 'is_active' => true],
            ['code' => 'ANALG', 'name' => 'Analgesics', 'is_active' => true],
            ['code' => 'ANTIH', 'name' => 'Antihypertensives', 'is_active' => true],
            ['code' => 'ANTID', 'name' => 'Antidiabetics', 'is_active' => true],
            ['code' => 'CVS', 'name' => 'Cardiovascular', 'is_active' => true],
            ['code' => 'CNS', 'name' => 'Central Nervous System', 'is_active' => true],
            ['code' => 'GI', 'name' => 'Gastrointestinal', 'is_active' => true],
            ['code' => 'RESP', 'name' => 'Respiratory', 'is_active' => true],
            ['code' => 'DERM', 'name' => 'Dermatological', 'is_active' => true],
            ['code' => 'VIT', 'name' => 'Vitamins & Supplements', 'is_active' => true],
        ];
        
        DB::table('pharmacy_categories')->insert($categories);
        $categoryIds = DB::table('pharmacy_categories')->pluck('id', 'code');
        
        // Seed Units
        $units = [
            ['name' => 'Tablet', 'short_name' => 'tab'],
            ['name' => 'Capsule', 'short_name' => 'cap'],
            ['name' => 'Milliliter', 'short_name' => 'ml'],
            ['name' => 'Gram', 'short_name' => 'g'],
            ['name' => 'Milligram', 'short_name' => 'mg'],
            ['name' => 'Injection', 'short_name' => 'inj'],
            ['name' => 'Syrup', 'short_name' => 'syr'],
            ['name' => 'Drop', 'short_name' => 'drop'],
            ['name' => 'Cream', 'short_name' => 'cream'],
            ['name' => 'Ointment', 'short_name' => 'oint'],
            ['name' => 'Suppository', 'short_name' => 'supp'],
            ['name' => 'Aerosol', 'short_name' => 'aero'],
        ];
        
        DB::table('pharmacy_units')->insert($units);
        $unitIds = DB::table('pharmacy_units')->pluck('id', 'short_name');
        
        // Seed Routes
        $routes = [
            ['name' => 'Oral', 'short_name' => 'PO'],
            ['name' => 'Intravenous', 'short_name' => 'IV'],
            ['name' => 'Intramuscular', 'short_name' => 'IM'],
            ['name' => 'Subcutaneous', 'short_name' => 'SC'],
            ['name' => 'Topical', 'short_name' => 'TOP'],
            ['name' => 'Inhalation', 'short_name' => 'INH'],
            ['name' => 'Rectal', 'short_name' => 'PR'],
            ['name' => 'Vaginal', 'short_name' => 'PV'],
            ['name' => 'Sublingual', 'short_name' => 'SL'],
            ['name' => 'Ophthalmic', 'short_name' => 'OPH'],
            ['name' => 'Otic', 'short_name' => 'OTIC'],
        ];
        
        DB::table('pharmacy_routes')->insert($routes);
        $routeIds = DB::table('pharmacy_routes')->pluck('id', 'short_name');
        
        // Seed Frequencies
        $frequencies = [
            ['name' => 'Once daily', 'short_code' => 'OD', 'times' => 1],
            ['name' => 'Twice daily', 'short_code' => 'BID', 'times' => 2],
            ['name' => 'Three times daily', 'short_code' => 'TID', 'times' => 3],
            ['name' => 'Four times daily', 'short_code' => 'QID', 'times' => 4],
            ['name' => 'Every 6 hours', 'short_code' => 'Q6H', 'times' => 4],
            ['name' => 'Every 8 hours', 'short_code' => 'Q8H', 'times' => 3],
            ['name' => 'Every 12 hours', 'short_code' => 'Q12H', 'times' => 2],
            ['name' => 'At bedtime', 'short_code' => 'HS', 'times' => 1],
            ['name' => 'As needed', 'short_code' => 'PRN', 'times' => 0],
            ['name' => 'Before meals', 'short_code' => 'AC', 'times' => 3],
            ['name' => 'After meals', 'short_code' => 'PC', 'times' => 3],
            ['name' => 'Weekly', 'short_code' => 'WEEKLY', 'times' => 1],
        ];
        
        DB::table('pharmacy_frequencies')->insert($frequencies);
        
        // Seed Pharmacy Items
        $medicines = [
            [
                'code' => 'AMOX500',
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'category_id' => $categoryIds['ANTIB'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '500mg',
                'is_prescription_required' => true,
                'is_active' => true,
            ],
            [
                'code' => 'PARA500',
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Paracetamol',
                'category_id' => $categoryIds['ANALG'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '500mg',
                'is_prescription_required' => false,
                'is_active' => true,
            ],
            [
                'code' => 'IBU400',
                'name' => 'Ibuprofen 400mg',
                'generic_name' => 'Ibuprofen',
                'category_id' => $categoryIds['ANALG'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '400mg',
                'is_prescription_required' => false,
                'is_active' => true,
            ],
            [
                'code' => 'ATEN50',
                'name' => 'Atenolol 50mg',
                'generic_name' => 'Atenolol',
                'category_id' => $categoryIds['ANTIH'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '50mg',
                'is_prescription_required' => true,
                'is_active' => true,
            ],
            [
                'code' => 'METF500',
                'name' => 'Metformin 500mg',
                'generic_name' => 'Metformin',
                'category_id' => $categoryIds['ANTID'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '500mg',
                'is_prescription_required' => true,
                'is_active' => true,
            ],
            [
                'code' => 'ASPIR75',
                'name' => 'Aspirin 75mg',
                'generic_name' => 'Aspirin',
                'category_id' => $categoryIds['CVS'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '75mg',
                'is_prescription_required' => true,
                'is_active' => true,
            ],
            [
                'code' => 'OMEP20',
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'category_id' => $categoryIds['GI'],
                'unit_id' => $unitIds['cap'],
                'route_id' => $routeIds['PO'],
                'strength' => '20mg',
                'is_prescription_required' => true,
                'is_active' => true,
            ],
            [
                'code' => 'SALB100',
                'name' => 'Salbutamol Inhaler',
                'generic_name' => 'Salbutamol',
                'category_id' => $categoryIds['RESP'],
                'unit_id' => $unitIds['aero'],
                'route_id' => $routeIds['INH'],
                'strength' => '100mcg/dose',
                'is_prescription_required' => true,
                'is_active' => true,
            ],
            [
                'code' => 'VITC500',
                'name' => 'Vitamin C 500mg',
                'generic_name' => 'Ascorbic Acid',
                'category_id' => $categoryIds['VIT'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '500mg',
                'is_prescription_required' => false,
                'is_active' => true,
            ],
            [
                'code' => 'CETIR10',
                'name' => 'Cetirizine 10mg',
                'generic_name' => 'Cetirizine',
                'category_id' => $categoryIds['CNS'],
                'unit_id' => $unitIds['tab'],
                'route_id' => $routeIds['PO'],
                'strength' => '10mg',
                'is_prescription_required' => false,
                'is_active' => true,
            ],
        ];
        
        DB::table('pharmacy_items')->insert($medicines);
        $medicineIds = DB::table('pharmacy_items')->pluck('id', 'code');
        
        // Seed Batches
        $batches = [];
        $batchNumbers = ['BATCH001', 'BATCH002', 'BATCH003', 'BATCH004', 'BATCH005'];
        $today = now();
        
        foreach ($medicineIds as $code => $medicineId) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $expiryDate = $today->copy()->addMonths(rand(6, 36))->format('Y-m-d');
                $purchasePrice = rand(50, 5000) / 100; // $0.50 to $50.00
                $sellingPrice = $purchasePrice * (1 + (rand(20, 50) / 100)); // 20-50% markup
                
                $batches[] = [
                    'medicine_id' => $medicineId,
                    'batch_number' => $batchNumbers[array_rand($batchNumbers)] . '-' . rand(100, 999),
                    'expiry_date' => $expiryDate,
                    'quantity' => rand(50, 500),
                    'purchase_price' => $purchasePrice,
                    'selling_price' => round($sellingPrice, 2),
                    'is_active' => true,
                    'created_at' => $today,
                    'updated_at' => $today,
                ];
            }
        }
        
        DB::table('pharmacy_batches')->insert($batches);
        
        $this->command->info('Pharmacy system seeded successfully!');
        $this->command->info('Categories: ' . count($categories));
        $this->command->info('Units: ' . count($units));
        $this->command->info('Routes: ' . count($routes));
        $this->command->info('Frequencies: ' . count($frequencies));
        $this->command->info('Medicines: ' . count($medicines));
        $this->command->info('Batches: ' . count($batches));
    }
}