<?php

namespace Database\Seeders;

use App\Models\Consumable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsumableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          $items = [
            [
                'name' => 'Oxygen Cylinder',
                'code' => 'CON-OXY-001',
                'category' => 'oxygen',
                'unit' => 'cylinder',
                'current_stock' => 20,
                'minimum_stock' => 5,
                'unit_cost' => 1500,
                'billable' => true,
            ],
            [
                'name' => 'Glucose 5% 500ml',
                'code' => 'CON-GLU-001',
                'category' => 'iv-fluid',
                'unit' => 'bottle',
                'current_stock' => 100,
                'minimum_stock' => 20,
                'unit_cost' => 120,
                'billable' => true,
            ],
            [
                'name' => 'Syringe 5ml',
                'code' => 'CON-SYR-005',
                'category' => 'disposable',
                'unit' => 'pcs',
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'unit_cost' => 5,
                'billable' => false,
            ],
            [
                'name' => 'Latex Gloves',
                'code' => 'CON-GLV-001',
                'category' => 'disposable',
                'unit' => 'box',
                'current_stock' => 50,
                'minimum_stock' => 10,
                'unit_cost' => 250,
                'billable' => false,
            ],
            [
                'name' => 'Surgical Balm',
                'code' => 'CON-BLM-001',
                'category' => 'dressing',
                'unit' => 'pcs',
                'current_stock' => 60,
                'minimum_stock' => 15,
                'unit_cost' => 80,
                'billable' => false,
            ],
        ];

        foreach ($items as $item) {
            Consumable::firstOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
