<?php

namespace Database\Seeders;

use App\Models\CuppingLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuppingLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $locations = [
            ['name' => 'Head', 'description' => 'Head and scalp area', 'status' => true],
            ['name' => 'Back', 'description' => 'Upper and lower back', 'status' => true],
            ['name' => 'Leg', 'description' => 'Thigh and calf areas', 'status' => true],
            ['name' => 'Shoulder', 'description' => 'Shoulder blades and traps', 'status' => true],
            ['name' => 'Arm', 'description' => 'Upper and lower arms', 'status' => true],
            ['name' => 'Neck', 'description' => 'Neck and cervical area', 'status' => true],
            ['name' => 'Chest', 'description' => 'Chest and pectoral area', 'status' => true],
            ['name' => 'Abdomen', 'description' => 'Abdominal area', 'status' => true],
        ];

        foreach ($locations as $location) {
            CuppingLocation::create($location);
        }
    }
}
