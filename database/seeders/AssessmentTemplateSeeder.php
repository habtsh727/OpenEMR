<?php

namespace Database\Seeders;

use App\Models\AssessmentTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssessmentTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $diagnoses = [
            'Malaria',
            'Pneumonia',
            'Upper Respiratory Tract Infection',
            'Hypertension',
            'Diabetes Mellitus',
            'Gastroenteritis',
        ];

        foreach ($diagnoses as $dx) {
            AssessmentTemplate::create([
                'diagnosis' => $dx,
                'context' => 'OPD',
            ]);
        }
    }
}
