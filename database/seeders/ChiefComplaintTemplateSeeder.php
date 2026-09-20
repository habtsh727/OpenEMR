<?php

namespace Database\Seeders;

use App\Models\ChiefComplaintTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChiefComplaintTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
          ChiefComplaintTemplate::insert([
            ['name' => 'Fever'],
            ['name' => 'Cough'],
            ['name' => 'Headache'],
            ['name' => 'Chest Pain'],
            ['name' => 'Abdominal Pain'],
            ['name' => 'Shortness of Breath'],
            ['name' => 'Vomiting'],
            ['name' => 'Diarrhea'],
        ]);
    }
}
