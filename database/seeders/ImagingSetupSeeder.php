<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagingSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $imagingTypes = [
            // X-Ray Studies
            ['name' => 'X-Ray (Plain Film)', 'fee' => 1200.00, 'description' => 'Standard radiographic imaging', 'is_active' => true],
            ['name' => 'Digital X-Ray', 'fee' => 1500.00, 'description' => 'Digital radiography with enhanced imaging', 'is_active' => true],
            ['name' => 'Portable X-Ray', 'fee' => 2000.00, 'description' => 'Bedside radiographic imaging', 'is_active' => true],
            ['name' => 'Contrast X-Ray', 'fee' => 3500.00, 'description' => 'X-ray with contrast media', 'is_active' => true],
            ['name' => 'Fluoroscopy', 'fee' => 6000.00, 'description' => 'Real-time moving X-ray images', 'is_active' => true],

            // CT Scans
            ['name' => 'CT Scan (Non-Contrast)', 'fee' => 8000.00, 'description' => 'Computed tomography without contrast', 'is_active' => true],
            ['name' => 'CT Scan (With Contrast)', 'fee' => 12000.00, 'description' => 'Computed tomography with IV contrast', 'is_active' => true],
            ['name' => 'High-Resolution CT', 'fee' => 15000.00, 'description' => 'Detailed CT imaging for lung assessment', 'is_active' => true],
            ['name' => 'CT Angiography', 'fee' => 18000.00, 'description' => 'CT for blood vessel imaging', 'is_active' => true],
            ['name' => 'CT Perfusion', 'fee' => 20000.00, 'description' => 'CT for blood flow assessment', 'is_active' => true],

            // MRI Studies
            ['name' => 'MRI (T1/T2 Weighted)', 'fee' => 15000.00, 'description' => 'Standard magnetic resonance imaging', 'is_active' => true],
            ['name' => 'MRI (With Contrast)', 'fee' => 20000.00, 'description' => 'MRI with gadolinium contrast', 'is_active' => true],
            ['name' => 'MRI Diffusion', 'fee' => 22000.00, 'description' => 'Diffusion-weighted imaging', 'is_active' => true],
            ['name' => 'MRI Perfusion', 'fee' => 25000.00, 'description' => 'Perfusion-weighted imaging', 'is_active' => true],
            ['name' => 'MRA (MR Angiography)', 'fee' => 22000.00, 'description' => 'MRI for blood vessel imaging', 'is_active' => true],
            ['name' => 'MR Spectroscopy', 'fee' => 28000.00, 'description' => 'Metabolic imaging using MRI', 'is_active' => true],
            ['name' => 'fMRI', 'fee' => 30000.00, 'description' => 'Functional MRI for brain activity', 'is_active' => true],

            // Ultrasound
            ['name' => 'Ultrasound (Abdomen)', 'fee' => 3000.00, 'description' => 'Abdominal sonography', 'is_active' => true],
            ['name' => 'Ultrasound (Pelvis)', 'fee' => 3500.00, 'description' => 'Pelvic sonography', 'is_active' => true],
            ['name' => 'Ultrasound (Obstetric)', 'fee' => 4000.00, 'description' => 'Pregnancy ultrasound', 'is_active' => true],
            ['name' => 'Doppler Ultrasound', 'fee' => 5000.00, 'description' => 'Blood flow ultrasound', 'is_active' => true],
            ['name' => '3D/4D Ultrasound', 'fee' => 6000.00, 'description' => 'Three-dimensional ultrasound', 'is_active' => true],
            ['name' => 'Echocardiography', 'fee' => 7000.00, 'description' => 'Cardiac ultrasound', 'is_active' => true],

            // Mammography
            ['name' => 'Mammography (Screening)', 'fee' => 2500.00, 'description' => 'Routine breast cancer screening', 'is_active' => true],
            ['name' => 'Mammography (Diagnostic)', 'fee' => 3500.00, 'description' => 'Diagnostic breast imaging', 'is_active' => true],
            ['name' => 'Breast Ultrasound', 'fee' => 4000.00, 'description' => 'Ultrasound of breast tissue', 'is_active' => true],
            ['name' => 'Breast MRI', 'fee' => 20000.00, 'description' => 'MRI for breast imaging', 'is_active' => true],

            // Nuclear Medicine
            ['name' => 'PET Scan', 'fee' => 30000.00, 'description' => 'Positron emission tomography', 'is_active' => true],
            ['name' => 'PET-CT', 'fee' => 40000.00, 'description' => 'Combined PET and CT imaging', 'is_active' => true],
            ['name' => 'Bone Scan', 'fee' => 8000.00, 'description' => 'Nuclear medicine bone imaging', 'is_active' => true],
            ['name' => 'Thyroid Scan', 'fee' => 6000.00, 'description' => 'Thyroid nuclear medicine study', 'is_active' => true],
            ['name' => 'V/Q Scan', 'fee' => 10000.00, 'description' => 'Ventilation/perfusion lung scan', 'is_active' => true],

            // Special Procedures
            ['name' => 'DEXA Scan', 'fee' => 3500.00, 'description' => 'Bone density measurement', 'is_active' => true],
            ['name' => 'Angiography', 'fee' => 25000.00, 'description' => 'Catheter-based vessel imaging', 'is_active' => true],
            ['name' => 'Myelography', 'fee' => 15000.00, 'description' => 'Spinal canal imaging with contrast', 'is_active' => true],
            ['name' => 'Arthrography', 'fee' => 12000.00, 'description' => 'Joint imaging with contrast', 'is_active' => true],
            ['name' => 'Hysterosalpingography', 'fee' => 10000.00, 'description' => 'Uterine and fallopian tube imaging', 'is_active' => true],

            // Emergency & Portable
            ['name' => 'FAST Scan', 'fee' => 2000.00, 'description' => 'Focused assessment with sonography for trauma', 'is_active' => true],
            ['name' => 'POCUS', 'fee' => 2500.00, 'description' => 'Point-of-care ultrasound', 'is_active' => true],
            ['name' => 'Emergency CT', 'fee' => 15000.00, 'description' => 'Priority CT scanning', 'is_active' => true],
        ];

        // Add timestamps to each record
        foreach ($imagingTypes as &$type) {
            $type['created_at'] = now();
            $type['updated_at'] = now();
        }

        DB::table('imaging_types')->insert($imagingTypes);

        $this->command->info('✅ ' . count($imagingTypes) . ' imaging types seeded successfully!');
        $this->command->info('💰 Total imaging fees: ₱' . number_format(array_sum(array_column($imagingTypes, 'fee')), 2));
    }
}
