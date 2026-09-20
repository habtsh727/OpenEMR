<?php

namespace Database\Seeders;

use App\Models\VitalType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VitalTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $vitals = [
            [
                'name' => 'Blood Pressure (Systolic)',
                'slug' => 'bp_systolic',
                'data_type' => 'number',
                'unit' => 'mmHg',
                'sort_order' => 10,
            ],
            [
                'name' => 'Blood Pressure (Diastolic)',
                'slug' => 'bp_diastolic',
                'data_type' => 'number',
                'unit' => 'mmHg',
                'sort_order' => 20,
            ],
            [
                'name' => 'Temperature',
                'slug' => 'temperature',
                'data_type' => 'number',
                'unit' => '°C',
                'sort_order' => 30,
            ],
            [
                'name' => 'Pulse Rate',
                'slug' => 'pulse_rate',
                'data_type' => 'number',
                'unit' => 'bpm',
                'sort_order' => 40,
            ],
            [
                'name' => 'SpO2',
                'slug' => 'spo2',
                'data_type' => 'number',
                'unit' => '%',
                'sort_order' => 50,
            ],
            [
                'name' => 'Respiratory Rate',
                'slug' => 'respiratory_rate',
                'data_type' => 'number',
                'unit' => 'breaths/min',
                'sort_order' => 60,
            ],
            [
                'name' => 'Pain Level',
                'slug' => 'pain_level',
                'data_type' => 'select',
                'options' => ['none', 'mild', 'moderate', 'severe'],
                'sort_order' => 70,
            ],
            [
                'name' => 'Consciousness Level',
                'slug' => 'consciousness',
                'data_type' => 'select',
                'options' => ['alert', 'confused', 'drowsy', 'unresponsive'],
                'sort_order' => 80,
            ],
        ];

        foreach ($vitals as $vital) {
            VitalType::updateOrCreate(
                ['slug' => $vital['slug']],
                $vital
            );
        }
    }
}
