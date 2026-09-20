<?php

namespace Database\Seeders;

use App\Models\LabTest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tests = [
            [
                'code' => 'CBC',
                'name' => 'Complete Blood Count',
                'sample_type' => 'blood',
                'department' => 'Hematology',
                'price' => 300,
            ],
            [
                'code' => 'FBS',
                'name' => 'Fasting Blood Sugar',
                'sample_type' => 'blood',
                'department' => 'Chemistry',
                'price' => 200,
            ],
            [
                'code' => 'URINE',
                'name' => 'Urinalysis',
                'sample_type' => 'urine',
                'department' => 'Microbiology',
                'price' => 150,
            ],
            [
                'code' => 'STOOL',
                'name' => 'Stool Examination',
                'sample_type' => 'stool',
                'department' => 'Parasitology',
                'price' => 180,
            ],
        ];

        foreach ($tests as $test) {
            LabTest::create($test);
        }
    }
}
