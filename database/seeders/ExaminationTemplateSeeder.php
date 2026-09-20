<?php

namespace Database\Seeders;

use App\Models\ExaminationTemplate;
use App\Models\ExaminEationTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExaminationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['system' => 'General', 'name' => 'General condition', 'field_type' => 'text'],
            ['system' => 'Vitals', 'name' => 'Temperature (°C)', 'field_type' => 'number'],
            ['system' => 'Vitals', 'name' => 'Blood Pressure', 'field_type' => 'text'],
            ['system' => 'Respiratory', 'name' => 'Wheezing', 'field_type' => 'yes_no'],
            ['system' => 'Abdomen', 'name' => 'Abdominal tenderness', 'field_type' => 'yes_no'],
            [
                'system' => 'Neurology',
                'name' => 'Level of consciousness',
                'field_type' => 'select',
                'options' => json_encode(['Alert', 'Drowsy', 'Unconscious'])
            ],
        ];

        foreach ($data as $item) {
            ExaminationTemplate::create($item);
        }
    }
}
