<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            // Dashboard
            ['name' => 'dashboard.read', 'display_name' => 'View Dashboard', 'category' => 'dashboard', 'action' => 'read', 'resource' => 'dashboard'],
            ['name' => 'dashboard.manage', 'display_name' => 'Manage Dashboard', 'category' => 'dashboard', 'action' => 'manage', 'resource' => 'dashboard'],
            
            // User Management
            ['name' => 'users.create', 'display_name' => 'Create Users', 'category' => 'users', 'action' => 'create', 'resource' => 'users'],
            ['name' => 'users.read', 'display_name' => 'View Users', 'category' => 'users', 'action' => 'read', 'resource' => 'users'],
            ['name' => 'users.update', 'display_name' => 'Edit Users', 'category' => 'users', 'action' => 'update', 'resource' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'category' => 'users', 'action' => 'delete', 'resource' => 'users'],
            
            // Client Management
            ['name' => 'clients.create', 'display_name' => 'Create Clients', 'category' => 'clients', 'action' => 'create', 'resource' => 'clients'],
            ['name' => 'clients.read', 'display_name' => 'View Clients', 'category' => 'clients', 'action' => 'read', 'resource' => 'clients'],
            ['name' => 'clients.update', 'display_name' => 'Edit Clients', 'category' => 'clients', 'action' => 'update', 'resource' => 'clients'],
            ['name' => 'clients.delete', 'display_name' => 'Delete Clients', 'category' => 'clients', 'action' => 'delete', 'resource' => 'clients'],
            
            // Contract Management
            ['name' => 'contracts.create', 'display_name' => 'Create Contracts', 'category' => 'contracts', 'action' => 'create', 'resource' => 'contracts'],
            ['name' => 'contracts.read', 'display_name' => 'View Contracts', 'category' => 'contracts', 'action' => 'read', 'resource' => 'contracts'],
            ['name' => 'contracts.update', 'display_name' => 'Edit Contracts', 'category' => 'contracts', 'action' => 'update', 'resource' => 'contracts'],
            ['name' => 'contracts.delete', 'display_name' => 'Delete Contracts', 'category' => 'contracts', 'action' => 'delete', 'resource' => 'contracts'],
            
            // Payment Management
            ['name' => 'payments.create', 'display_name' => 'Create Payments', 'category' => 'payments', 'action' => 'create', 'resource' => 'payments'],
            ['name' => 'payments.read', 'display_name' => 'View Payments', 'category' => 'payments', 'action' => 'read', 'resource' => 'payments'],
            ['name' => 'payments.update', 'display_name' => 'Edit Payments', 'category' => 'payments', 'action' => 'update', 'resource' => 'payments'],
            ['name' => 'payments.delete', 'display_name' => 'Delete Payments', 'category' => 'payments', 'action' => 'delete', 'resource' => 'payments'],
            
            // Visit Management
            ['name' => 'visits.create', 'display_name' => 'Create Visit', 'category' => 'visits', 'action' => 'create', 'resource' => 'visits'],
            ['name' => 'visits.read', 'display_name' => 'View Visit', 'category' => 'visits', 'action' => 'read', 'resource' => 'visits'],
            ['name' => 'visits.update', 'display_name' => 'Edit Visit', 'category' => 'visits', 'action' => 'update', 'resource' => 'visits'],
            ['name' => 'visits.delete', 'display_name' => 'Delete Visit', 'category' => 'visits', 'action' => 'delete', 'resource' => 'visits'],
            
            // Machine Management
            ['name' => 'machines.create', 'display_name' => 'Create Machines', 'category' => 'machines', 'action' => 'create', 'resource' => 'machines'],
            ['name' => 'machines.read', 'display_name' => 'View Machines', 'category' => 'machines', 'action' => 'read', 'resource' => 'machines'],
            ['name' => 'machines.update', 'display_name' => 'Edit Machines', 'category' => 'machines', 'action' => 'update', 'resource' => 'machines'],
            ['name' => 'machines.delete', 'display_name' => 'Delete Machines', 'category' => 'machines', 'action' => 'delete', 'resource' => 'machines'],
            
            // Address Management
            ['name' => 'addresses.create', 'display_name' => 'Create Addresses', 'category' => 'addresses', 'action' => 'create', 'resource' => 'addresses'],
            ['name' => 'addresses.read', 'display_name' => 'View Addresses', 'category' => 'addresses', 'action' => 'read', 'resource' => 'addresses'],
            ['name' => 'addresses.update', 'display_name' => 'Edit Addresses', 'category' => 'addresses', 'action' => 'update', 'resource' => 'addresses'],
            ['name' => 'addresses.delete', 'display_name' => 'Delete Addresses', 'category' => 'addresses', 'action' => 'delete', 'resource' => 'addresses'],
            
            // System Management
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles', 'category' => 'system', 'action' => 'manage', 'resource' => 'roles'],
            ['name' => 'features.manage', 'display_name' => 'Manage Features', 'category' => 'system', 'action' => 'manage', 'resource' => 'features'],
            ['name' => 'system.settings', 'display_name' => 'System Settings', 'category' => 'system', 'action' => 'manage', 'resource' => 'settings'],
            ['name' => 'logs.read', 'display_name' => 'View System Logs', 'category' => 'system', 'action' => 'read', 'resource' => 'logs'],
            
            // Reports
            ['name' => 'reports.financial', 'display_name' => 'Financial Reports', 'category' => 'reports', 'action' => 'view', 'resource' => 'financial_reports'],
            ['name' => 'reports.contracts', 'display_name' => 'Contract Reports', 'category' => 'reports', 'action' => 'view', 'resource' => 'contract_reports'],
            ['name' => 'reports.clients', 'display_name' => 'Client Reports', 'category' => 'reports', 'action' => 'view', 'resource' => 'client_reports'],
            
            // Export Features (Super Admin Only)
            ['name' => 'contracts.export.excel', 'display_name' => 'Export Contracts to Excel', 'category' => 'exports', 'action' => 'export', 'resource' => 'contracts_excel'],
            ['name' => 'contracts.export.pdf', 'display_name' => 'Export Contracts to PDF', 'category' => 'exports', 'action' => 'export', 'resource' => 'contracts_pdf'],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['name' => $feature['name']],
                $feature
            );
        }
    }
}
