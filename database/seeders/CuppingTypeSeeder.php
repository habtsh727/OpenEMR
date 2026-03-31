<?php

namespace Database\Seeders;

use App\Models\CuppingType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuppingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $types = [
            ['name' => 'Full', 'description' => 'Full body cupping therapy', 'status' => true],
            ['name' => 'Half', 'description' => 'Half body cupping therapy', 'status' => true],
            ['name' => 'Dry', 'description' => 'Dry cupping therapy (suction only)', 'status' => true],
            ['name' => 'Wet', 'description' => 'Wet cupping therapy (with incisions)', 'status' => true],
            ['name' => 'Facial', 'description' => 'Facial cupping therapy', 'status' => true],
            ['name' => 'Sports', 'description' => 'Sports cupping therapy for athletes', 'status' => true],
        ];
        foreach ($types as $type) {
            CuppingType::create($type);
        }
    }
}
