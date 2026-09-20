<?php

namespace Database\Seeders;

use App\Models\RehabTreatmentType;
use Illuminate\Database\Seeder;

class RehabTreatmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Physical Therapy',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>',
                'description' => 'Record physical therapy sessions, exercises, and progress',
                'order' => 1,
                'fields' => [
                    ['name' => 'exercise_type', 'label' => 'Exercise Type', 'type' => 'text', 'required' => true, 'placeholder' => 'e.g., Range of motion, Strengthening'],
                    ['name' => 'duration', 'label' => 'Duration (minutes)', 'type' => 'number', 'required' => true, 'min' => 1, 'max' => 180],
                    ['name' => 'repetitions', 'label' => 'Repetitions', 'type' => 'number', 'required' => false],
                    ['name' => 'pain_level', 'label' => 'Pain Level (1-10)', 'type' => 'number', 'min' => 1, 'max' => 10],
                    ['name' => 'assistance', 'label' => 'Assistance Required', 'type' => 'select', 'options' => [
                        ['value' => 'independent', 'label' => 'Independent'],
                        ['value' => 'minimal', 'label' => 'Minimal Assistance'],
                        ['value' => 'moderate', 'label' => 'Moderate Assistance'],
                        ['value' => 'maximal', 'label' => 'Maximal Assistance'],
                        ['value' => 'dependent', 'label' => 'Dependent'],
                    ]],
                ],
            ],
            [
                'name' => 'Vital Signs',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                'description' => 'Record patient vital signs and measurements',
                'order' => 2,
                'fields' => [
                    ['name' => 'blood_pressure', 'label' => 'Blood Pressure', 'type' => 'blood_pressure', 'required' => true, 'help' => 'Format: 120/80'],
                    ['name' => 'heart_rate', 'label' => 'Heart Rate (bpm)', 'type' => 'number', 'required' => true, 'min' => 40, 'max' => 200],
                    ['name' => 'temperature', 'label' => 'Temperature (°C)', 'type' => 'number', 'required' => true, 'step' => 0.1, 'min' => 35, 'max' => 42],
                    ['name' => 'respiratory_rate', 'label' => 'Respiratory Rate', 'type' => 'number', 'min' => 8, 'max' => 40],
                    ['name' => 'oxygen_saturation', 'label' => 'O2 Saturation (%)', 'type' => 'number', 'min' => 70, 'max' => 100],
                ],
            ],
            [
                'name' => 'Medication Administration',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 3v9a1 1 0 01-1 1h-4a1 1 0 01-1-1V7L8 4z"></path></svg>',
                'description' => 'Record medications administered during treatment',
                'order' => 3,
                'fields' => [
                    ['name' => 'medication', 'label' => 'Medication Name', 'type' => 'text', 'required' => true],
                    ['name' => 'dosage', 'label' => 'Dosage', 'type' => 'text', 'required' => true],
                    ['name' => 'route', 'label' => 'Route', 'type' => 'select', 'options' => [
                        ['value' => 'oral', 'label' => 'Oral'],
                        ['value' => 'iv', 'label' => 'Intravenous'],
                        ['value' => 'im', 'label' => 'Intramuscular'],
                        ['value' => 'subcutaneous', 'label' => 'Subcutaneous'],
                        ['value' => 'topical', 'label' => 'Topical'],
                    ]],
                    // Removed the problematic line with auth()->user()->name
                ],
            ],
            [
                'name' => 'Daily Assessment',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>',
                'description' => 'Daily progress assessment and observations',
                'order' => 4,
                'fields' => [
                    ['name' => 'mood', 'label' => 'Mood (1-10)', 'type' => 'number', 'min' => 1, 'max' => 10],
                    ['name' => 'pain_level', 'label' => 'Pain Level (1-10)', 'type' => 'number', 'min' => 1, 'max' => 10],
                    ['name' => 'appetite', 'label' => 'Appetite', 'type' => 'select', 'options' => [
                        ['value' => 'poor', 'label' => 'Poor'],
                        ['value' => 'fair', 'label' => 'Fair'],
                        ['value' => 'good', 'label' => 'Good'],
                        ['value' => 'excellent', 'label' => 'Excellent'],
                    ]],
                    ['name' => 'sleep_hours', 'label' => 'Sleep (hours)', 'type' => 'number', 'min' => 0, 'max' => 24, 'step' => 0.5],
                    ['name' => 'goals_met', 'label' => 'Goals Met Today', 'type' => 'textarea'],
                ],
            ],
            [
                'name' => 'Progress Note',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>',
                'description' => 'General progress notes and observations',
                'order' => 5,
                'fields' => [
                    ['name' => 'progress', 'label' => 'Progress Notes', 'type' => 'textarea', 'required' => true],
                    ['name' => 'goals', 'label' => 'Goals for Next Session', 'type' => 'textarea'],
                    ['name' => 'barriers', 'label' => 'Barriers Encountered', 'type' => 'textarea'],
                ],
            ],
        ];

        foreach ($types as $type) {
            RehabTreatmentType::create($type);
        }
        
        $this->command->info('Treatment types seeded successfully!');
    }
}