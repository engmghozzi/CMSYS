<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class ClearPermissionCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:clear-cache {--user= : Clear cache for specific user email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all permission caches for users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userEmail = $this->option('user');
        
        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
            if (!$user) {
                $this->error("User with email '{$userEmail}' not found.");
                return 1;
            }
            $this->clearUserPermissionCache($user);
            $this->info("Permission cache cleared for user: {$user->email}");
        } else {
            $users = User::all();
            $this->info("Clearing permission cache for {$users->count()} users...");
            
            foreach ($users as $user) {
                $this->clearUserPermissionCache($user);
            }
            
            $this->info("Permission cache cleared for all users.");
        }
        
        return 0;
    }
    
    private function clearUserPermissionCache(User $user)
    {
        // Clear all permission caches for this user
        $permissions = [
            'dashboard.read', 'dashboard.manage',
            'users.create', 'users.read', 'users.update', 'users.delete',
            'clients.create', 'clients.read', 'clients.update', 'clients.delete',
            'addresses.create', 'addresses.read', 'addresses.update', 'addresses.delete',
            'contracts.create', 'contracts.read', 'contracts.update', 'contracts.delete',
            'payments.create', 'payments.read', 'payments.update', 'payments.delete',
            'roles.manage', 'features.manage', 'system.settings', 'logs.read',
            'reports.financial', 'reports.contracts', 'reports.clients',
            'contracts.export.excel', 'contracts.export.pdf'
        ];
        
        foreach ($permissions as $permission) {
            $cacheKey = "user_permission_{$user->id}_{$permission}";
            Cache::forget($cacheKey);
        }
    }
}
