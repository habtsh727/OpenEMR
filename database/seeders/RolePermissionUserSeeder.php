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

        // Give permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());

        $adminRole->givePermissionTo([
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
            'view_patient',
            'create_diagnosis',
            'create_lab_order',
            'create_prescription',
            'view_lab_result',
        ]);

        $laboratoryRole->givePermissionTo([
            'view_lab_order',
            'enter_lab_result',
            'verify_lab_result',
        ]);

        $pharmacyRole->givePermissionTo([
            'view_prescription',
            'dispense_drug',
            'manage_drugs',
        ]);

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('SuperAdmin123'),
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
                    'employee_code' => strtoupper($u['role']) . '-001',
                    'status' => 'active',
                ]
            );
        }
    }
}
