<?php
// database/seeders/RehabPackageSeeder.php

namespace Database\Seeders;

use App\Models\RehabPackage;
use App\Models\RehabPackageItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class RehabPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Get first user as creator (or create a default one)
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
        }

        // Package 1: Basic Rehabilitation Package
        $package1 = RehabPackage::create([
            'name' => 'Basic Rehabilitation Package',
            'description' => 'Essential rehabilitation services for post-surgery recovery',
            'base_price' => 1500.00,
            'discount_type' => 'fixed',
            'discount_value' => 100.00,
            'final_price' => 1400.00,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        // Standard Medications
        $package1->items()->createMany([
            [
                'item_type' => 'standard_medication',
                'item_name' => 'Paracetamol',
                'dosage' => '500mg',
                'frequency' => 'Three times daily',
                'duration' => '7 days',
                'quantity' => '21 tablets',
                'instructions' => 'Take after meals',
                'notes' => 'For pain management',
            ],
            [
                'item_type' => 'standard_medication',
                'item_name' => 'Ibuprofen',
                'dosage' => '400mg',
                'frequency' => 'Twice daily',
                'duration' => '5 days',
                'quantity' => '10 tablets',
                'instructions' => 'Take with food',
                'notes' => 'Anti-inflammatory',
            ],
        ]);

        // Services
        $package1->items()->createMany([
            [
                'item_type' => 'service',
                'item_name' => 'Physical Therapy Session',
                'instructions' => '60-minute session with physiotherapist',
                'notes' => 'Includes assessment and exercises',
            ],
            [
                'item_type' => 'service',
                'item_name' => 'Occupational Therapy',
                'instructions' => '45-minute session focusing on daily activities',
                'notes' => 'Weekly assessment',
            ],
        ]);

        // Bed
        $package1->items()->create([
            'item_type' => 'bed',
            'item_name' => 'Bed Accommodation',
            'bed_duration_days' => 7,
            'instructions' => 'Private room with attached bathroom',
            'notes' => 'Includes meals and nursing care',
        ]);

        // Package 2: Premium Rehabilitation Package
        $package2 = RehabPackage::create([
            'name' => 'Premium Rehabilitation Package',
            'description' => 'Comprehensive rehabilitation with advanced therapies',
            'base_price' => 3500.00,
            'discount_type' => 'percentage',
            'discount_value' => 15.00,
            'final_price' => 2975.00,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        // Standard Medications
        $package2->items()->createMany([
            [
                'item_type' => 'standard_medication',
                'item_name' => 'Tramadol',
                'dosage' => '50mg',
                'frequency' => 'Every 6 hours',
                'duration' => '10 days',
                'quantity' => '40 capsules',
                'instructions' => 'Take as needed for severe pain',
                'notes' => 'Prescription required',
            ],
            [
                'item_type' => 'standard_medication',
                'item_name' => 'Diazepam',
                'dosage' => '5mg',
                'frequency' => 'Twice daily',
                'duration' => '7 days',
                'quantity' => '14 tablets',
                'instructions' => 'For muscle spasms',
                'notes' => 'May cause drowsiness',
            ],
            [
                'item_type' => 'standard_medication',
                'item_name' => 'Multivitamin Complex',
                'dosage' => 'One tablet',
                'frequency' => 'Once daily',
                'duration' => '30 days',
                'quantity' => '30 tablets',
                'instructions' => 'Take in the morning',
                'notes' => 'General wellness',
            ],
        ]);

        // Custom Medications
        $package2->items()->createMany([
            [
                'item_type' => 'custom_medication',
                'item_name' => 'Special Pain Relief Compound',
                'dosage' => '10ml',
                'frequency' => 'Three times daily',
                'duration' => '14 days',
                'quantity' => '420ml',
                'instructions' => 'Shake well before use',
                'notes' => 'Compounded specially',
            ],
        ]);

        // Services
        $package2->items()->createMany([
            [
                'item_type' => 'service',
                'item_name' => 'Intensive Physical Therapy',
                'instructions' => '90-minute daily sessions with senior therapist',
                'notes' => 'Includes hydrotherapy',
            ],
            [
                'item_type' => 'service',
                'item_name' => 'Speech Therapy',
                'instructions' => '45-minute sessions, 3 times per week',
                'notes' => 'For communication disorders',
            ],
            [
                'item_type' => 'service',
                'item_name' => 'Psychological Counseling',
                'instructions' => '60-minute weekly sessions',
                'notes' => 'Emotional support and coping strategies',
            ],
            [
                'item_type' => 'service',
                'item_name' => 'Nutritional Consultation',
                'instructions' => 'Initial assessment and weekly follow-ups',
                'notes' => 'Personalized meal planning',
            ],
        ]);

        // Bed
        $package2->items()->create([
            'item_type' => 'bed',
            'item_name' => 'Deluxe Bed Accommodation',
            'bed_duration_days' => 14,
            'instructions' => 'Executive suite with VIP amenities',
            'notes' => 'Includes TV, WiFi, and private attendant',
        ]);

        // Package 3: Custom Medication Package (No discounts)
        $package3 = RehabPackage::create([
            'name' => 'Custom Therapy Package',
            'description' => 'Tailored rehabilitation with custom medications',
            'base_price' => 2500.00,
            'discount_type' => null,
            'discount_value' => null,
            'final_price' => 2500.00,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        // Custom Medications
        $package3->items()->createMany([
            [
                'item_type' => 'custom_medication',
                'item_name' => 'Compound Pain Cream',
                'dosage' => 'Apply thin layer',
                'frequency' => 'Four times daily',
                'duration' => '30 days',
                'quantity' => '100g tube',
                'instructions' => 'Massage gently into affected area',
                'notes' => 'Topical analgesic',
            ],
            [
                'item_type' => 'custom_medication',
                'item_name' => 'Herbal Supplement Blend',
                'dosage' => '2 capsules',
                'frequency' => 'Twice daily',
                'duration' => '60 days',
                'quantity' => '240 capsules',
                'instructions' => 'Take with meals',
                'notes' => 'Natural anti-inflammatory',
            ],
        ]);

        // Services
        $package3->items()->createMany([
            [
                'item_type' => 'service',
                'item_name' => 'Acupuncture Sessions',
                'instructions' => '45-minute sessions, twice weekly',
                'notes' => 'For pain management',
            ],
            [
                'item_type' => 'service',
                'item_name' => 'Massage Therapy',
                'instructions' => '60-minute deep tissue massage',
                'notes' => 'Weekly sessions',
            ],
        ]);

        // Package 4: Quick Recovery Package (Inactive)
        $package4 = RehabPackage::create([
            'name' => 'Quick Recovery Package',
            'description' => 'Intensive short-term rehabilitation',
            'base_price' => 1800.00,
            'discount_type' => 'fixed',
            'discount_value' => 200.00,
            'final_price' => 1600.00,
            'is_active' => false,
            'created_by' => $user->id,
        ]);

        $package4->items()->createMany([
            [
                'item_type' => 'standard_medication',
                'item_name' => 'Ketorolac',
                'dosage' => '10mg',
                'frequency' => 'Every 8 hours',
                'duration' => '5 days',
                'quantity' => '15 tablets',
                'instructions' => 'Short-term pain relief',
                'notes' => 'Maximum 5 days use',
            ],
            [
                'item_type' => 'service',
                'item_name' => 'Accelerated PT Program',
                'instructions' => 'Daily intensive sessions',
                'notes' => '5 days a week',
            ],
            [
                'item_type' => 'bed',
                'item_name' => 'Standard Bed',
                'bed_duration_days' => 5,
                'instructions' => 'Shared room accommodation',
                'notes' => 'Basic amenities',
            ],
        ]);

        $this->command->info('Rehab packages seeded successfully!');
    }
}