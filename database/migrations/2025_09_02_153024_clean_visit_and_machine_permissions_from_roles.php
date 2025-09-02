<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('roles')) {
            $roles = Role::all();
            
            foreach ($roles as $role) {
                $permissions = $role->permissions ?? [];
                
                // Remove visit and machine permissions
                $permissions = array_filter($permissions, function($permission) {
                    return !str_contains($permission, 'visit') && !str_contains($permission, 'machine');
                });
                
                // Update the role with cleaned permissions
                $role->update(['permissions' => array_values($permissions)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration is not reversible as we're cleaning up
        // unused permissions. If you need to restore these,
        // you would need to recreate the original permissions.
    }
};
