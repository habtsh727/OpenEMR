<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== CREATE ALL PERMISSIONS ====================

        $permissions = [
            // Dashboard
            'view_dashboard',
            'access_home',

            // ===== REFERRALS =====
            'create_referral',
            'view_referral_queue',
            'view_referral',
            'submit_referral_result',
            'print_referral',
            // Settings
            'access_settings_profile',
            'access_settings_password',
            'access_settings_appearance',

            // Accounts (Admin only)
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'manage_roles',
            'view_employee',
            'create_employee',
            'update_employee',
            'delete_employee',

            // Patients
            'create_patient',
            'view_patients',
            'view_patient',
            'update_patient',
            'view_patient_profile',
            'view_patient_finance',
            'view_patient_payments',

            // Cashier - ALL Payments
            'manage_card_fee',
            'view_payments',
            'view_payment_details',
            'create_invoice',
            'view_invoice',
            'receive_payment',
            'refund_payment',
            'view_lab_payments',
            'view_imaging_payments',
            'cashier_imaging_payments',
            'view_cashier_medication_orders',
            'process_walkin_payment',
            'view_rehab_cashier_queue',
            'process_rehab_cashier_payment',
            'process_cupping_payment',
            'view_registration_payment_report',
            'view_lab_payment_report',
            'view_imaging_payment_report',
            'view_pharmacy_payment_report',
            'view_rehab_payment_report',
            'view_bed_payment_report',
            'view_cupping_sales_report',
            'view_walkin_sales_report',

            // Laboratory
            'create_lab_order',
            'view_lab_dashboard',
            'collect_lab_sample',
            'enter_lab_result',
            'report_lab_result',
            'verify_lab_result',
            'view_lab_results',
            'manage_lab_tests',
            'view_lab_tests',
            'view_doctor_lab_results',
            'view_patient_lab_results',

            // Imaging / Radiology
            'create_imaging_order',
            'view_imaging_results',
            'view_radiology_dashboard',
            'upload_imaging_result',
            'verify_imaging_result',
            'manage_imaging_types',
            'manage_body_parts',
            'view_imaging_order',

            // Medication / Pharmacy
            'create_medication_order',
            'view_medication_orders',
            'view_cashier_medication_orders',
            'view_pharmacy_dashboard',
            'manage_pharmacy_items',
            'view_pharmacy_items',
            'manage_pharmacy_masters',
            'view_pharmacy_masters',
            'manage_pharmacy_batches',
            'view_pharmacy_batches',
            'manage_custom_medications',
            'view_custom_medications',
            'dispense_drug',
            'view_prescription',
            'download_prescription',
            'print_prescription',
            'view_pharmacy_sales_report',
            'view_pharmacy_payment_report',
            'manage_consumables',
            'manage_inventory',

            // Walk-in
            'create_walkin_order',
            'dispense_walkin_medication',
            'view_walkin_sales_report',

            // Doctor Consultation
            'view_doctor_queue',
            'manage_medical_history',
            'manage_chief_complaint',
            'manage_examination',
            'manage_assessment',
            'create_diagnosis',
            'create_prescription',

            // Triage / Nursing
            'view_triage_encounters',
            'record_vitals',
            'update_vitals',
            'manage_vital_types',

            // Appointments
            'create_appointment',
            'create_doctor_appointment',
            'view_today_appointments',
            'view_doctor_today_appointments',
            'view_upcoming_appointments',
            'view_doctor_upcoming_appointments',
            'view_all_appointments',
            'view_doctor_all_appointments',
            'view_appointment_calendar',
            'view_doctor_appointment_calendar',
            'view_appointment_reports',
            'view_doctor_appointment_reports',
            'view_appointment_details',
            'view_doctor_appointment_details',

            // Rehabilitation
            'view_rehab_queue',
            'fill_rehab_questionnaire',
            'create_rehab_order',
            'review_rehab_order',
            'view_doctor_rehab_queue',
            'manage_rehab_templates',
            'view_rehab_templates',
            'create_rehab_template',
            'edit_rehab_template',
            'manage_rehab_template_questions',
            'manage_rehab_packages',
            'view_rehab_packages',
            'create_rehab_package',
            'edit_rehab_package',
            'view_rehab_treatment_queue',
            'manage_rehab_treatment',
            'manage_rehab_treatment_types',
            'manage_rehab_bed_selection',
            'manage_rehab_bed',
            'view_rehab_finance_report',

            // Cupping
            'create_cupping_order',
            'view_cupping_treatment_queue',
            'manage_cupping_treatment',
            'view_cupping_results',
            'manage_cupping_admin_packages',
            'manage_cupping_packages',
            'manage_cupping_types',
            'manage_cupping_locations',
            'view_cupping_sales_report',

            // Bed Management
            'view_beds',
            'manage_beds',
            'create_bed',
            'update_bed',
            'delete_bed',
            'assign_bed',
            'transfer_bed',
            'discharge_bed',

            // Configuration
            'manage_medical_history_templates',
            'manage_chief_complaint_templates',
            'manage_examination_templates',
            'manage_assessment_templates',

            // Services
            'manage_service_categories',
            'manage_services',

            // Reports (View only)
            'view_reports',
            'export_reports',
            'view_bed_payment_report',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==================== CREATE ROLES & ASSIGN PERMISSIONS ====================

        // 1. SUPER ADMIN - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. ADMIN
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_patient_finance',
            'manage_service_categories',
            'manage_services',
            'manage_medical_history_templates',
            'manage_chief_complaint_templates',
            'manage_examination_templates',
            'manage_assessment_templates',
            'manage_pharmacy_items',
            'manage_pharmacy_masters',
            'manage_pharmacy_batches',
            'manage_rehab_packages',
            'manage_rehab_templates',
            'manage_cupping_admin_packages',
            'manage_cupping_types',
            'manage_cupping_locations',
            'manage_imaging_types',
            'manage_body_parts',
            'manage_lab_tests',
            'manage_beds',
            'manage_consumables',
            'manage_vital_types',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'manage_roles',
            'view_employee',
            'create_employee',
            'update_employee',
            'delete_employee',
            'view_reports',
        ]);

        // 3. REGISTRATION - No payment permissions
        $registration = Role::firstOrCreate(['name' => 'registration']);
        $registration->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'create_patient',
            'view_patient_profile',
            'update_patient',
        ]);

        // 4. CASHIER - ALL Payments
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'manage_card_fee',
            'view_payments',
            'view_payment_details',
            'create_invoice',
            'view_invoice',
            'receive_payment',
            'refund_payment',
            'view_lab_payments',
            'view_imaging_payments',
            'cashier_imaging_payments',
            'view_cashier_medication_orders',
            'process_walkin_payment',
            'view_rehab_cashier_queue',
            'process_rehab_cashier_payment',
            'process_cupping_payment',
            'view_registration_payment_report',
            'view_lab_payment_report',
            'view_imaging_payment_report',
            'view_pharmacy_payment_report',
            'view_rehab_payment_report',
            'view_bed_payment_report',
            'view_cupping_sales_report',
            'view_walkin_sales_report',
        ]);

        // 5. NURSE / TRIAGE
        $nurse = Role::firstOrCreate(['name' => 'nurse']);
        $nurse->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_triage_encounters',
            'record_vitals',
            'update_vitals',
            'manage_vital_types',
        ]);

        // 6. DOCTOR
        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $doctor->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_doctor_queue',
            'manage_medical_history',
            'manage_chief_complaint',
            'manage_examination',
            'manage_assessment',
            'create_diagnosis',
            'create_prescription',
            'download_prescription',
            'print_prescription',
            'create_lab_order',
            'view_lab_results',
            'create_imaging_order',
            'view_imaging_results',
            'create_medication_order',
            'manage_custom_medications',
            'create_cupping_order',
            'view_cupping_results',
            'view_cupping_treatment_queue',
            'create_rehab_order',
            'review_rehab_order',
            'create_appointment',
            'view_today_appointments',
            'view_upcoming_appointments',
            'view_appointment_calendar',
            'view_appointment_details',
            'manage_medical_history_templates',
            'manage_chief_complaint_templates',

            'create_referral',
            'view_referral_queue',
            'view_referral',
            'submit_referral_result',
            'print_referral',
        ]);

        // 7. LABORATORY
        $laboratory = Role::firstOrCreate(['name' => 'laboratory']);
        $laboratory->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_lab_dashboard',
            'collect_lab_sample',
            'enter_lab_result',
            'report_lab_result',
            'verify_lab_result',
            'view_lab_results',
            'manage_lab_tests',
            'view_lab_tests',
        ]);

        // 8. PHARMACY
        $pharmacy = Role::firstOrCreate(['name' => 'pharmacy']);
        $pharmacy->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_pharmacy_dashboard',
            'view_pharmacy_items',
            'manage_pharmacy_items',
            'view_pharmacy_masters',
            'manage_pharmacy_masters',
            'view_pharmacy_batches',
            'manage_pharmacy_batches',
            'view_prescription',
            'dispense_drug',
            'manage_custom_medications',
            'create_walkin_order',
            'dispense_walkin_medication',
            'view_pharmacy_sales_report',
            'view_walkin_sales_report',
            'view_pharmacy_payment_report',
            'manage_consumables',
            'manage_inventory',
        ]);

        // 9. RADIOLOGY
        $radiology = Role::firstOrCreate(['name' => 'radiology']);
        $radiology->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_radiology_dashboard',
            'upload_imaging_result',
            'verify_imaging_result',
            'view_imaging_results',
        ]);

        // 10. REHABILITATION
        $rehab = Role::firstOrCreate(['name' => 'rehab']);
        $rehab->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_rehab_queue',
            'fill_rehab_questionnaire',
            'view_rehab_treatment_queue',
            'manage_rehab_treatment',
            'manage_rehab_treatment_types',
            'view_rehab_packages',
            'view_rehab_templates',
            'view_rehab_finance_report',
        ]);

        // 11. BED MANAGER
        $bedManager = Role::firstOrCreate(['name' => 'bed-manager']);
        $bedManager->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_beds',
            'create_bed',
            'update_bed',
            'delete_bed',
            'assign_bed',
            'transfer_bed',
            'discharge_bed',
            'manage_rehab_bed_selection',
        ]);

        // 12. CUPPING THERAPIST
        $cuppingTherapist = Role::firstOrCreate(['name' => 'cupping-therapist']);
        $cuppingTherapist->givePermissionTo([
            'view_dashboard',
            'view_patients',
            'view_patient_profile',
            'view_cupping_treatment_queue',
            'manage_cupping_treatment',
            'view_cupping_results',
        ]);
    }
}
