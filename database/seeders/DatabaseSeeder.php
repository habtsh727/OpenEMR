<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            RolePermissionSeeder::class,
            RolePermissionUserSeeder::class,
            ChiefComplaintTemplateSeeder::class,
            MedicalHistoryTemplateSeeder::class,
            ExaminationTemplateSeeder::class,
            AssessmentTemplateSeeder::class,
            LabTestSeeder::class,
            BodyPartsSeeder::class,
            ImagingSetupSeeder::class,
            PharmacySystemSeeder::class,
            BedClassesTableSeeder::class,
            BedTypesTableSeeder::class,
            WardsTableSeeder::class,
            RoomsSeeder::class,
            BedsSeeder::class,
            VitalTypesSeeder::class,
            RehabQuestionnaireTemplateSeeder::class,
        ]);
    }
}
