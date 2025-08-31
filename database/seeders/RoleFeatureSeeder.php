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
                'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                'visits.create', 'visits.read', 'visits.update', 'visits.delete',
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
                'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                'visits.create', 'visits.read', 'visits.update', 'visits.delete',
                'reports.financial', 'reports.contracts', 'reports.clients'
            ],
            'supervisor' => [
                'clients.read', 'clients.create', 'clients.update', 'clients.delete',
                'addresses.read', 'addresses.create', 'addresses.update', 'addresses.delete',
                'contracts.read', 'contracts.create', 'contracts.update', 'contracts.delete',
                'payments.read', 'payments.create', 'payments.update', 'payments.delete',
                'machines.read', 'machines.create', 'machines.update', 'machines.delete',
                'visits.read', 'visits.create', 'visits.update', 'visits.delete',
                'reports.financial', 'reports.contracts', 'reports.clients'
            ],
            'employee' => [
                'clients.read', 'clients.create', 'clients.update',
                'addresses.read', 'addresses.create', 'addresses.update',
                'contracts.read', 'contracts.create', 'contracts.update',
                'payments.read', 'payments.create', 'payments.update', 
                'machines.read', 'machines.create', 'machines.update', 
                'visits.read', 'visits.create', 'visits.update',
            ],
            'accountant' => [
                'dashboard.read',
                'clients.read',
                'addresses.read',
                'contracts.read',
                'payments.create', 'payments.read', 'payments.update',
                'machines.read',
                'visits.read',
                'reports.financial'
            ],
            'viewer' => [
                'clients.read',
                'addresses.read',
                'contracts.read',
                'payments.read',
                'machines.read',
                'visits.read',
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
