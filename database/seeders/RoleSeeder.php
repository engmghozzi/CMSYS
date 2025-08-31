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
                    'dashboard.read', 'dashboard.manage',
                    'users.create', 'users.read', 'users.update', 'users.delete',
                    'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                    'addresses.create', 'addresses.read', 'addresses.update', 'addresses.delete',
                    'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                    'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                    'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                    'visits.create', 'visits.read', 'visits.update', 'visits.delete',
                    'roles.manage', 'features.manage', 'logs.read',
                    'reports.financial', 'reports.contracts', 'reports.clients'
                ],
                'is_active' => true
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrative access with most permissions',
                'permissions' => [
                    'dashboard.read',
                    'users.read', 'users.update',
                    'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                    'addresses.create', 'addresses.read', 'addresses.update', 'addresses.delete',
                    'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                    'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                    'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                    'visits.create', 'visits.read', 'visits.update', 'visits.delete',
                    'reports.financial', 'reports.contracts', 'reports.clients'
                ],
                'is_active' => true
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisor access with most permissions',
                'permissions' => [
                    'clients.read', 'clients.create', 'clients.update', 'clients.delete',
                    'addresses.read', 'addresses.create', 'addresses.update', 'addresses.delete',
                    'contracts.read', 'contracts.create', 'contracts.update', 'contracts.delete',
                    'payments.read', 'payments.create', 'payments.update', 'payments.delete',
                    'machines.read', 'machines.create', 'machines.update', 'machines.delete',
                    'visits.read', 'visits.create', 'visits.update', 'visits.delete',
                    'reports.financial', 'reports.contracts', 'reports.clients'
                ],
                'is_active' => true
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'Basic access for daily operations',
                'permissions' => [
                    'clients.read', 'clients.create', 'clients.update',
                    'addresses.read', 'addresses.create', 'addresses.update',
                    'contracts.read', 'contracts.create', 'contracts.update',
                    'payments.read', 'payments.create', 'payments.update',
                    'machines.read', 'machines.create', 'machines.update',
                    'visits.read', 'visits.create', 'visits.update',
                ],
                'is_active' => true
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Financial and payment management access',
                'permissions' => [
                    'dashboard.read',
                    'clients.read',
                    'addresses.read',
                    'contracts.read',
                    'payments.create', 'payments.read', 'payments.update',
                    'machines.read', 
                    'visits.read', 
                    'reports.financial'
                ],
                'is_active' => true
            ],
            [
                'name' => 'viewer',
                'display_name' => 'Viewer',
                'description' => 'Read-only access for viewing data',
                'permissions' => [
                    'clients.read',
                    'addresses.read',
                    'contracts.read',
                    'payments.read',
                    'machines.read',
                    'visits.read',
                    'reports.financial'
                ],
                'is_active' => true
            ],
            [
                'name' => 'technician',
                'display_name' => 'Technician',
                'description' => 'Technician who can view and update only assigned visits',
                'permissions' => [
                    'visits.read',
                    'visits.update'
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
