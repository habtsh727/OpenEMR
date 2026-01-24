<?php

namespace Database\Seeders;

use App\Models\MedicalHistoryTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalHistoryTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MedicalHistoryTemplate::insert([
            ['name' => 'Diabetes', 'field_type' => 'yes_no'],
            ['name' => 'Hypertension', 'field_type' => 'yes_no'],
            ['name' => 'Covid-19 History', 'field_type' => 'date'],
            ['name' => 'Known Allergies', 'field_type' => 'text'],
        ]);
    }
}
