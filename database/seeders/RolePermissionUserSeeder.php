<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionUserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Users & Roles
            'create lab order',
            'pay lab order',
            'collect sample',
            'report lab result',

            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'manage_roles',

            // Employees
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
            'create_imaging_order',  // Added for radiology
            'view_imaging_result',   // Added for radiology

            // Laboratory
            'view_lab_order',
            'enter_lab_result',
            'verify_lab_result',

            // Radiology - Added new permissions
            'view_imaging_order',
            'upload_imaging_result',
            'verify_imaging_result',
            'manage_imaging_equipment',

            // Pharmacy
            'view_prescription',
            'dispense_drug',
            'manage_drugs',

            // Store
            'manage_inventory',
            'issue_items',

            // Reports
            'view_reports',
            'export_reports',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
            $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $registrationRole = Role::firstOrCreate(['name' => 'registration']);
            $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
            $nurseRole = Role::firstOrCreate(['name' => 'nurse']);
            $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
            $laboratoryRole = Role::firstOrCreate(['name' => 'laboratory']);
            $pharmacyRole = Role::firstOrCreate(['name' => 'pharmacy']);
            $radiologyRole = Role::firstOrCreate(['name' => 'radiology']);  // Added Radiology role

        // Give permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole->givePermissionTo([
            'create lab order',
            'pay lab order',
            'collect sample',
            'report lab result',

            'view_reports',
            'export_reports',
            'manage_inventory',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'manage_roles',
            'view_employee',
            'create_employee',
            'update_employee',
            'delete_employee',
            'manage_services',
        ]);

        $registrationRole->givePermissionTo([
            'create_patient',
            'view_patient',
            'update_patient',
        ]);

        $cashierRole->givePermissionTo([
            'pay lab order',
            'create_invoice',
            'view_invoice',
            'receive_payment',
            'refund_payment',
        ]);

        $nurseRole->givePermissionTo([
            'view_patient',
            'record_vitals',
            'update_vitals',
        ]);

        $doctorRole->givePermissionTo([
            'create lab order',
            'view_patient',
            'create_diagnosis',
            'create_lab_order',
            'create_prescription',
            'view_lab_result',
            'create_imaging_order',  // Doctors can create imaging orders
            'view_imaging_result',   // Doctors can view imaging results
        ]);

        $laboratoryRole->givePermissionTo([
            'collect sample', 
            'report lab result',
            'view_lab_order',
            'enter_lab_result',
            'verify_lab_result',
        ]);

        $pharmacyRole->givePermissionTo([
            'view_prescription',
            'dispense_drug',
            'manage_drugs',
        ]);

        // Radiology role permissions
        $radiologyRole->givePermissionTo([
            'view_imaging_order',
            'upload_imaging_result',
            'verify_imaging_result',
            'manage_imaging_equipment',
            'view_patient',  // Radiology staff may need to view patient info
        ]);

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        // Create sample users for each role
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin'],
            ['name' => 'Registration User', 'email' => 'registration@example.com', 'role' => 'registration'],
            ['name' => 'Cashier User', 'email' => 'cashier@example.com', 'role' => 'cashier'],
            ['name' => 'Nurse User', 'email' => 'nurse@example.com', 'role' => 'nurse'],
            ['name' => 'Doctor User', 'email' => 'doctor@example.com', 'role' => 'doctor'],
            ['name' => 'Lab User', 'email' => 'lab@example.com', 'role' => 'laboratory'],
            ['name' => 'Pharmacy User', 'email' => 'pharmacy@example.com', 'role' => 'pharmacy'],
            ['name' => 'Radiology User', 'email' => 'radiology@example.com', 'role' => 'radiology'],  // Added Radiology user
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('Password123'),
                ]
            );

            // Assign role
            $user->assignRole($u['role']);

            // Create employee record
            Employee::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'role' => $u['role'],
                    'employee_code' => strtoupper(substr($u['role'], 0, 3)) . '-001',  // Modified to handle longer role names
                    'status' => 'active',
                ]
            );
        }
    }
}