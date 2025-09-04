<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Feature;
use Illuminate\Database\Seeder;

class RoleFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all roles and features
        $roles = Role::all();
        $features = Feature::all()->keyBy('name');

        // Define role-feature mappings based on permissions
        $roleFeatures = [
            'super_admin' => [
                'dashboard.read', 'dashboard.manage',
                'users.create', 'users.read', 'users.update', 'users.delete',
                'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                'addresses.create', 'addresses.read', 'addresses.update', 'addresses.delete',
                'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                'roles.manage', 'features.manage', 'system.settings', 'logs.read',
                'reports.financial', 'reports.contracts', 'reports.clients',
                'contracts.export.excel', 'contracts.export.pdf'
            ],
            'admin' => [
                'dashboard.read',
                'users.read', 'users.update',
                'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                'addresses.create', 'addresses.read', 'addresses.update', 'addresses.delete',
                'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                'reports.financial', 'reports.contracts', 'reports.clients'
            ],
            'supervisor' => [
                'clients.read', 'clients.create', 'clients.update', 'clients.delete',
                'addresses.read', 'addresses.create', 'addresses.update', 'addresses.delete',
                'contracts.read', 'contracts.create', 'contracts.update', 'contracts.delete',
                'payments.read', 'payments.create', 'payments.update', 'payments.delete',
                'reports.financial', 'reports.contracts', 'reports.clients'
            ],
            'employee' => [
                'dashboard.read',
                'clients.read', 'clients.create', 'clients.update',
                'addresses.read', 'addresses.create', 'addresses.update',
                'contracts.read', 'contracts.create', 'contracts.update',
                'payments.read', 'payments.create', 'payments.update'
            ],
            'accountant' => [
                'dashboard.read',
                'clients.read',
                'addresses.read',
                'contracts.read',
                'payments.create', 'payments.read', 'payments.update',
                'reports.financial'
            ],
            'viewer' => [
                'clients.read',
                'addresses.read',
                'contracts.read',
                'payments.read',
                'reports.financial'
            ]
        ];

        foreach ($roles as $role) {
            if (isset($roleFeatures[$role->name])) {
                $featureIds = [];
                foreach ($roleFeatures[$role->name] as $featureName) {
                    if (isset($features[$featureName])) {
                        $featureIds[$features[$featureName]->id] = ['is_granted' => true];
                    }
                }
                
                // Sync features to role
                $role->features()->sync($featureIds);
            }
        }
    }
}
