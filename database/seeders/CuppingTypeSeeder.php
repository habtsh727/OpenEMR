<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CuppingType;

class CuppingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Full', 'description' => 'Full body cupping therapy', 'status' => true],
            ['name' => 'Half', 'description' => 'Half body cupping therapy', 'status' => true],
            ['name' => 'Dry', 'description' => 'Dry cupping therapy', 'status' => true],
            ['name' => 'Wet', 'description' => 'Wet cupping therapy (Hijama)', 'status' => true],
        ];

        foreach ($types as $type) {
            CuppingType::create($type);
        }
    }
}