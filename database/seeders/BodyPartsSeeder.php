<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BodyPartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $bodyParts = [
            // Head & Neck
            ['name' => 'Brain', 'code' => 'BRAIN', 'description' => 'Cerebral imaging including hemispheres, cerebellum, and brainstem', 'is_active' => true],
            ['name' => 'Skull', 'code' => 'SKULL', 'description' => 'Bony structures of the cranium', 'is_active' => true],
            ['name' => 'Sinuses', 'code' => 'SINUS', 'description' => 'Paranasal sinuses imaging', 'is_active' => true],
            ['name' => 'Orbits', 'code' => 'ORBIT', 'description' => 'Eye sockets and ocular structures', 'is_active' => true],
            ['name' => 'Face', 'code' => 'FACE', 'description' => 'Facial bones and soft tissues', 'is_active' => true],
            ['name' => 'TMJ', 'code' => 'TMJ', 'description' => 'Temporomandibular joint', 'is_active' => true],
            ['name' => 'Thyroid', 'code' => 'THYROID', 'description' => 'Thyroid gland imaging', 'is_active' => true],
            ['name' => 'Neck Soft Tissue', 'code' => 'NECKST', 'description' => 'Cervical soft tissues and lymph nodes', 'is_active' => true],

            // Chest & Thorax
            ['name' => 'Chest', 'code' => 'CHEST', 'description' => 'Lungs, heart, and thoracic cavity', 'is_active' => true],
            ['name' => 'Heart', 'code' => 'HEART', 'description' => 'Cardiac imaging and chambers', 'is_active' => true],
            ['name' => 'Lungs', 'code' => 'LUNGS', 'description' => 'Pulmonary parenchyma and pleura', 'is_active' => true],
            ['name' => 'Mediastinum', 'code' => 'MEDIAST', 'description' => 'Central thoracic compartment', 'is_active' => true],
            ['name' => 'Ribs', 'code' => 'RIBS', 'description' => 'Thoracic cage bones', 'is_active' => true],

            // Abdomen
            ['name' => 'Abdomen', 'code' => 'ABD', 'description' => 'General abdominal imaging', 'is_active' => true],
            ['name' => 'Liver', 'code' => 'LIVER', 'description' => 'Hepatic imaging', 'is_active' => true],
            ['name' => 'Gallbladder', 'code' => 'GB', 'description' => 'Biliary system imaging', 'is_active' => true],
            ['name' => 'Pancreas', 'code' => 'PANCREAS', 'description' => 'Pancreatic imaging', 'is_active' => true],
            ['name' => 'Spleen', 'code' => 'SPLEEN', 'description' => 'Splenic imaging', 'is_active' => true],
            ['name' => 'Kidneys', 'code' => 'KIDNEY', 'description' => 'Renal imaging', 'is_active' => true],
            ['name' => 'Adrenals', 'code' => 'ADRENAL', 'description' => 'Adrenal glands imaging', 'is_active' => true],
            ['name' => 'Stomach', 'code' => 'STOMACH', 'description' => 'Gastric imaging', 'is_active' => true],
            ['name' => 'Intestines', 'code' => 'INTEST', 'description' => 'Small and large bowel imaging', 'is_active' => true],

            // Pelvis
            ['name' => 'Pelvis', 'code' => 'PELVIS', 'description' => 'General pelvic imaging', 'is_active' => true],
            ['name' => 'Bladder', 'code' => 'BLADDER', 'description' => 'Urinary bladder imaging', 'is_active' => true],
            ['name' => 'Prostate', 'code' => 'PROSTATE', 'description' => 'Prostatic gland imaging', 'is_active' => true],
            ['name' => 'Uterus', 'code' => 'UTERUS', 'description' => 'Uterine imaging', 'is_active' => true],
            ['name' => 'Ovaries', 'code' => 'OVARY', 'description' => 'Ovarian imaging', 'is_active' => true],

            // Spine
            ['name' => 'Cervical Spine', 'code' => 'CSPINE', 'description' => 'C1-C7 vertebrae', 'is_active' => true],
            ['name' => 'Thoracic Spine', 'code' => 'TSPINE', 'description' => 'T1-T12 vertebrae', 'is_active' => true],
            ['name' => 'Lumbar Spine', 'code' => 'LSPINE', 'description' => 'L1-L5 vertebrae', 'is_active' => true],
            ['name' => 'Sacrum', 'code' => 'SACRUM', 'description' => 'Sacral bone imaging', 'is_active' => true],
            ['name' => 'Coccyx', 'code' => 'COCCYX', 'description' => 'Tailbone imaging', 'is_active' => true],

            // Upper Extremity
            ['name' => 'Shoulder', 'code' => 'SHOULDER', 'description' => 'Shoulder joint and rotator cuff', 'is_active' => true],
            ['name' => 'Arm', 'code' => 'ARM', 'description' => 'Upper arm imaging', 'is_active' => true],
            ['name' => 'Elbow', 'code' => 'ELBOW', 'description' => 'Elbow joint imaging', 'is_active' => true],
            ['name' => 'Forearm', 'code' => 'FOREARM', 'description' => 'Forearm bones and soft tissues', 'is_active' => true],
            ['name' => 'Wrist', 'code' => 'WRIST', 'description' => 'Carpal bones and wrist joint', 'is_active' => true],
            ['name' => 'Hand', 'code' => 'HAND', 'description' => 'Metacarpals and phalanges', 'is_active' => true],
            ['name' => 'Fingers', 'code' => 'FINGERS', 'description' => 'Digital imaging', 'is_active' => true],

            // Lower Extremity
            ['name' => 'Hip', 'code' => 'HIP', 'description' => 'Hip joint and acetabulum', 'is_active' => true],
            ['name' => 'Thigh', 'code' => 'THIGH', 'description' => 'Femur and thigh muscles', 'is_active' => true],
            ['name' => 'Knee', 'code' => 'KNEE', 'description' => 'Knee joint and patella', 'is_active' => true],
            ['name' => 'Leg', 'code' => 'LEG', 'description' => 'Tibia, fibula, and calf', 'is_active' => true],
            ['name' => 'Ankle', 'code' => 'ANKLE', 'description' => 'Ankle joint and malleoli', 'is_active' => true],
            ['name' => 'Foot', 'code' => 'FOOT', 'description' => 'Tarsal and metatarsal bones', 'is_active' => true],
            ['name' => 'Toes', 'code' => 'TOES', 'description' => 'Pedal digits imaging', 'is_active' => true],

            // Special
            ['name' => 'Breast', 'code' => 'BREAST', 'description' => 'Mammary gland imaging', 'is_active' => true],
            ['name' => 'Whole Body', 'code' => 'WB', 'description' => 'Full body imaging', 'is_active' => true],
            ['name' => 'Vascular', 'code' => 'VASC', 'description' => 'Blood vessel imaging', 'is_active' => true],
            ['name' => 'Lymph Nodes', 'code' => 'LYMPH', 'description' => 'Lymphatic system imaging', 'is_active' => true],
        ];

        // Add timestamps to each record
        foreach ($bodyParts as &$part) {
            $part['created_at'] = now();
            $part['updated_at'] = now();
        }

        DB::table('body_parts')->insert($bodyParts);

        $this->command->info('✅ ' . count($bodyParts) . ' body parts seeded successfully!');
    }
}
