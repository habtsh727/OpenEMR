<?php

namespace Database\Seeders;

use App\Models\PharmacyFrequency;
use App\Models\RehabPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RehabPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
           $frequencies = PharmacyFrequency::pluck('id', 'name')->toArray();

        $packages = [
            [
                'name' => 'Basic Physiotherapy Package',
                'description' => 'Essential physiotherapy sessions for rehabilitation',
                'base_price' => 2500.00,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'items' => [
                    [
                        'item_type' => 'service',
                        'item_name' => 'Initial Assessment',
                        'notes' => 'Comprehensive physiotherapy assessment',
                    ],
                    [
                        'item_type' => 'service',
                        'item_name' => 'Physiotherapy Session',
                        'dosage' => '60 min',
                        'duration' => '5 sessions',
                        'notes' => 'One-on-one physiotherapy sessions',
                    ],
                ]
            ],
            [
                'name' => 'Comprehensive Rehab Package',
                'description' => 'Complete rehabilitation program with medications',
                'base_price' => 5000.00,
                'discount_type' => 'fixed',
                'discount_value' => 500.00,
                'items' => [
                    [
                        'item_type' => 'service',
                        'item_name' => 'Initial Consultation',
                        'notes' => 'Doctor consultation',
                    ],
                    [
                        'item_type' => 'service',
                        'item_name' => 'Physiotherapy',
                        'duration' => '10 sessions',
                    ],
                    [
                        'item_type' => 'standard_medication',
                        'item_name' => 'Pain Relief Medication',
                        'dosage' => '500mg',
                        'frequency_id' => $frequencies['Twice Daily'] ?? null,
                        'duration' => '14 days',
                    ],
                ]
            ],
            [
                'name' => 'Inpatient Rehab Package',
                'description' => 'Includes bed and daily therapy',
                'base_price' => 15000.00,
                'discount_type' => null,
                'discount_value' => null,
                'items' => [
                    [
                        'item_type' => 'bed',
                        'item_name' => 'Private Room',
                        'bed_duration_days' => 7,
                    ],
                    [
                        'item_type' => 'service',
                        'item_name' => 'Daily Physiotherapy',
                        'duration' => '7 days',
                    ],
                    [
                        'item_type' => 'service',
                        'item_name' => 'Occupational Therapy',
                        'duration' => '3 sessions',
                    ],
                ]
            ],
        ];

        foreach ($packages as $packageData) {
            $items = $packageData['items'];
            unset($packageData['items']);

            $package = RehabPackage::create($packageData);

            foreach ($items as $itemData) {
                $package->items()->create($itemData);
            }
        }
    }
}
