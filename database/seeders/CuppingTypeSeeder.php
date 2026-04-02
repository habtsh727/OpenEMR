<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CuppingType;

class CuppingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Full Cupping', 'description' => 'Complete body cupping therapy covering all major areas', 'status' => true],
            ['name' => 'Half Cupping', 'description' => 'Upper or lower body cupping therapy', 'status' => true],
            ['name' => 'Dry Cupping', 'description' => 'Traditional dry cupping using suction only', 'status' => true],
            ['name' => 'Wet Cupping', 'description' => 'Hijama - wet cupping therapy with minor incisions', 'status' => true],
            ['name' => 'Facial Cupping', 'description' => 'Cupping therapy for face and neck area', 'status' => true],
            ['name' => 'Sports Cupping', 'description' => 'Sports recovery and performance cupping', 'status' => true],
        ];

        foreach ($types as $type) {
            CuppingType::create($type);
        }
    }
}