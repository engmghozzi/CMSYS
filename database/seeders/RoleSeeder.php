<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system access with all permissions',
                'permissions' => [
                    'users.create', 'users.read', 'users.update', 'users.delete',
                    'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                    'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                    'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                    'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                    'roles.manage', 'features.manage', 'system.settings', 'logs.read'
                ],
                'is_active' => true
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrative access with most permissions',
                'permissions' => [
                    'users.read', 'users.update',
                    'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                    'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                    'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                    'machines.create', 'machines.read', 'machines.update', 'machines.delete'
                ],
                'is_active' => true
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Basic access for daily operations',
                'permissions' => [
                    'clients.read', 'clients.create', 'clients.update',
                    'contracts.read', 'contracts.create', 'contracts.update',
                    'payments.read', 'payments.create',
                    'machines.read'
                ],
                'is_active' => true
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Financial and payment management access',
                'permissions' => [
                    'clients.read',
                    'contracts.read',
                    'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                    'reports.financial'
                ],
                'is_active' => true
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
