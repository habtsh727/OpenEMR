<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            
            'manage_medical_history_templates',
            'manage_chief_complaint_templates',
            // Users & Roles
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'manage_roles',

            // Departments & Employees
            'view_department',
            'create_department',
            'update_department',
            'delete_department',

            'view_employee',
            'create_employee',
            'update_employee',
            'delete_employee',

            // Services
            'manage_services',

            // Registration
            'create_patient',
            'view_patient',
            'update_patient',

            // Cashier
            'create_invoice',
            'view_invoice',
            'receive_payment',
            'refund_payment',

            // Nursing
            'record_vitals',
            'update_vitals',

            // Doctor
            'create_diagnosis',
            'create_lab_order',
            'create_prescription',
            'view_lab_result',

            // Laboratory
            'view_lab_order',
            'enter_lab_result',
            'verify_lab_result',

            // Pharmacy
            'view_prescription',
            'dispense_drug',
            'manage_drugs',

            // Radiology
            'view_imaging_order',
            'upload_imaging_result',

            // Store
            'manage_inventory',
            'issue_items',

            // Reports
            'view_reports',
            'export_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ROLES
        Role::firstOrCreate(['name' => 'super-admin'])
            ->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => 'admin'])
            ->givePermissionTo([
                'manage_medical_history_templates', // ADD
                'manage_chief_complaint_templates', // ADD
                'view_reports',
                'export_reports',
                'manage_inventory',

                // ADMIN MANAGEMENT
                'view_user',
                'create_user',
                'update_user',
                'delete_user',
                'manage_roles',

                // DEPARTMENTS & EMPLOYEES
                'view_department',
                'create_department',
                'update_department',
                'delete_department',
                'view_employee',
                'create_employee',
                'update_employee',
                'delete_employee',

                // SERVICES
                'manage_services',
            ]);

        Role::firstOrCreate(['name' => 'registration'])
            ->givePermissionTo([
                'create_patient',
                'view_patient',
                'update_patient',
            ]);

        Role::firstOrCreate(['name' => 'cashier'])
            ->givePermissionTo([
                'create_invoice',
                'view_invoice',
                'receive_payment',
                'refund_payment',
            ]);

        Role::firstOrCreate(['name' => 'nurse'])
            ->givePermissionTo([
                'view_patient',
                'record_vitals',
                'update_vitals',
            ]);

        Role::firstOrCreate(['name' => 'doctor'])
            ->givePermissionTo([
                'view_patient',
                'create_diagnosis',
                'create_lab_order',
                'create_prescription',
                'view_lab_result',
                'manage_medical_history_templates', // ADD
                'manage_chief_complaint_templates', // ADD
            ]);

        Role::firstOrCreate(['name' => 'laboratory'])
            ->givePermissionTo([
                'view_lab_order',
                'enter_lab_result',
                'verify_lab_result',
            ]);

        Role::firstOrCreate(['name' => 'pharmacy'])
            ->givePermissionTo([
                'view_prescription',
                'dispense_drug',
                'manage_drugs',
            ]);
    }
}
