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
                'users.create', 'users.read', 'users.update', 'users.delete',
                'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                'roles.manage', 'features.manage', 'system.settings', 'logs.read',
                'reports.financial', 'reports.contracts', 'reports.clients'
            ],
            'admin' => [
                'users.read', 'users.update',
                'clients.create', 'clients.read', 'clients.update', 'clients.delete',
                'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
                'payments.create', 'payments.read', 'payments.update', 'payments.delete',
                'machines.create', 'machines.read', 'machines.update', 'machines.delete',
                'reports.financial', 'reports.contracts', 'reports.clients'
            ],
            'employee' => [
                'clients.read', 'clients.create', 'clients.update',
                'contracts.read', 'contracts.create', 'contracts.update',
                'payments.read', 'payments.create',
                'machines.read'
            ],
            'accountant' => [
                'clients.read',
                'contracts.read',
                'payments.create', 'payments.read', 'payments.update', 'payments.delete',
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
