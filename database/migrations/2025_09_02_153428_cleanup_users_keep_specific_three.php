<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            // Get the employee role
            $employeeRole = Role::where('name', 'employee')->first();
            
            // Only update mohamed if employee role exists
            if ($employeeRole) {
                User::where('email', 'mohamed@aliandothman.com.kw')
                    ->update(['role_id' => $employeeRole->id]);
            }
            
            // Delete all users except the three specified ones
            User::whereNotIn('email', [
                'mohamed@aliandothman.com.kw',
                'mahmoud@aliandothman.com.kw',
                'eng.m.yossry@gmail.com'
            ])->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration is not reversible as we're deleting users
        // If you need to restore users, you would need to recreate them manually
    }
};
