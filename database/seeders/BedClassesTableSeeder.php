<?php

namespace Database\Seeders;

use App\Models\BedClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BedClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          $bedClasses = [
            ['name' => 'General', 'code' => 'GEN', 'description' => 'Standard general bed', 'price_per_day' => 500, 'currency' => 'ETB'],
            ['name' => 'VIP', 'code' => 'VIP', 'description' => 'VIP private bed', 'price_per_day' => 1500, 'currency' => 'ETB'],
            ['name' => 'VVIP', 'code' => 'VVIP', 'description' => 'VVIP luxury bed', 'price_per_day' => 3000, 'currency' => 'ETB'],
        ];

        foreach ($bedClasses as $class) {
            BedClass::updateOrCreate(['code' => $class['code']], $class);
        }
    }
}
