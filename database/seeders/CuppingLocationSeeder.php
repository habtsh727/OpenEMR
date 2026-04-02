<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CuppingLocation;

class CuppingLocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Head', 'description' => 'Head, scalp, and temples area', 'status' => true],
            ['name' => 'Neck', 'description' => 'Neck and cervical spine area', 'status' => true],
            ['name' => 'Upper Back', 'description' => 'Upper back between shoulder blades', 'status' => true],
            ['name' => 'Middle Back', 'description' => 'Mid-back region', 'status' => true],
            ['name' => 'Lower Back', 'description' => 'Lumbar region', 'status' => true],
            ['name' => 'Shoulders', 'description' => 'Shoulder blades and deltoid muscles', 'status' => true],
            ['name' => 'Arms', 'description' => 'Upper and lower arms', 'status' => true],
            ['name' => 'Legs', 'description' => 'Thighs and calves', 'status' => true],
            ['name' => 'Abdomen', 'description' => 'Stomach and abdominal area', 'status' => true],
            ['name' => 'Chest', 'description' => 'Chest and pectoral muscles', 'status' => true],
        ];

        foreach ($locations as $location) {
            CuppingLocation::create($location);
        }
    }
}