<?php

namespace Database\Seeders;

use App\Models\Frequency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          $frequencies = [
            ['name' => 'Once Daily', 'code' => 'OD', 'times_per_day' => 1, 'description' => 'Once per day'],
            ['name' => 'Twice Daily', 'code' => 'BD', 'times_per_day' => 2, 'description' => 'Twice per day'],
            ['name' => 'Three Times Daily', 'code' => 'TDS', 'times_per_day' => 3, 'description' => 'Three times per day'],
            ['name' => 'Four Times Daily', 'code' => 'QID', 'times_per_day' => 4, 'description' => 'Four times per day'],
            ['name' => 'Every Morning', 'code' => 'OM', 'times_per_day' => 1, 'description' => 'Every morning'],
            ['name' => 'Every Night', 'code' => 'ON', 'times_per_day' => 1, 'description' => 'Every night'],
            ['name' => 'As Needed', 'code' => 'PRN', 'times_per_day' => null, 'description' => 'As needed'],
            ['name' => 'Weekly', 'code' => 'WEEKLY', 'times_per_day' => null, 'description' => 'Once weekly'],
        ];

        foreach ($frequencies as $frequency) {
            Frequency::create($frequency);
        }
    }
}
