<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CuppingLocation;

class CuppingLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Head', 'description' => 'Head and neck area', 'status' => true],
            ['name' => 'Back', 'description' => 'Upper, middle and lower back', 'status' => true],
            ['name' => 'Leg', 'description' => 'Thigh and calf areas', 'status' => true],
            ['name' => 'Shoulder', 'description' => 'Shoulder blades and traps', 'status' => true],
            ['name' => 'Arm', 'description' => 'Upper and lower arms', 'status' => true],
        ];

        foreach ($locations as $location) {
            CuppingLocation::create($location);
        }
    }
}