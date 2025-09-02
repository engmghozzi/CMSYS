<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any users with technician role to viewer role
        if (Schema::hasTable('roles') && Schema::hasTable('users')) {
            $viewerRole = Role::where('name', 'viewer')->first();
            if ($viewerRole) {
                User::whereHas('role', function($q) {
                    $q->where('name', 'technician');
                })->update(['role_id' => $viewerRole->id]);
            }
        }

        // Delete technician role if it exists
        if (Schema::hasTable('roles')) {
            Role::where('name', 'technician')->delete();
        }

        // Drop machines table if it exists
        if (Schema::hasTable('machines')) {
            Schema::dropIfExists('machines');
        }

        // Drop visits table if it exists
        if (Schema::hasTable('visits')) {
            Schema::dropIfExists('visits');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration is not reversible as we're cleaning up
        // unused tables and roles. If you need to restore these,
        // you would need to recreate the original migrations.
    }
};
