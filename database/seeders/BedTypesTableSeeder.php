<?php

namespace Database\Seeders;

use App\Models\BedType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BedTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $bedTypes = [
            ['name' => 'ICU', 'code' => 'ICU', 'description' => 'Intensive Care Unit bed'],
            ['name' => 'Pediatric', 'code' => 'PED', 'description' => 'Pediatric bed for children'],
            ['name' => 'Isolation', 'code' => 'ISO', 'description' => 'Isolation bed for infectious patients'],
            ['name' => 'General', 'code' => 'GEN', 'description' => 'General hospital bed'],
        ];

        foreach ($bedTypes as $type) {
            BedType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}
