<?php

namespace Database\Seeders;

use App\Models\RehabQuestionnaireTemplate;
use App\Models\RehabTemplateQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RehabQuestionnaireTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $template = RehabQuestionnaireTemplate::create([
            'title' => 'Basic Rehabilitation Assessment'
        ]);

        $questions = [

            [
                'question' => 'Is the patient conscious?',
                'type' => 'boolean',
                'options' => null,
                'is_required' => true,
                'order' => 1
            ],

            [
                'question' => 'Primary symptoms',
                'type' => 'checkbox',
                'options' => json_encode([
                    'Pain',
                    'Weakness',
                    'Breathing difficulty',
                    'Fatigue'
                ]),
                'is_required' => false,
                'order' => 2
            ],

            [
                'question' => 'Chief complaint',
                'type' => 'text',
                'options' => null,
                'is_required' => true,
                'order' => 3
            ],

            [
                'question' => 'Medical history notes',
                'type' => 'textarea',
                'options' => null,
                'is_required' => false,
                'order' => 4
            ],

            [
                'question' => 'Pain level (1-10)',
                'type' => 'number',
                'options' => null,
                'is_required' => false,
                'order' => 5
            ],

            [
                'question' => 'Assessment Date',
                'type' => 'datetime',
                'options' => null,
                'is_required' => true,
                'order' => 6
            ],

            [
                'question' => 'Rehabilitation Type',
                'type' => 'select',
                'options' => json_encode([
                    'Physical Therapy',
                    'Psychiatric Rehab',
                    'Respiratory Rehab',
                    'General Rehab'
                ]),
                'is_required' => true,
                'order' => 7
            ],
        ];

        foreach ($questions as $q) {
            RehabTemplateQuestion::create([
                'rehab_questionnaire_template_id' => $template->id,
                'question' => $q['question'],
                'type' => $q['type'],
                'options' => $q['options'],
                'is_required' => $q['is_required'],
                'order' => $q['order'],
            ]);
        }
    }
}
