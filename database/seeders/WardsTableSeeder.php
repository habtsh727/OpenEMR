<?php

namespace Database\Seeders;

use App\Models\Ward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          $wards = [
            ['name' => 'ICU Ward', 'code' => 'ICU'],
            ['name' => 'Medical Ward', 'code' => 'MED'],
            ['name' => 'Surgical Ward', 'code' => 'SURG'],
            ['name' => 'Pediatric Ward', 'code' => 'PED'],
        ];

        foreach ($wards as $ward) {
            Ward::updateOrCreate(['code' => $ward['code']], $ward);
        }
    }
}
