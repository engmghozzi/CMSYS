<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $accountantRole = Role::where('name', 'accountant')->first();

        // Create Default Super Admin (Mahmoud Ghozzi)
        $defaultSuperAdmin = User::updateOrCreate(
            ['email' => 'eng.m.yossry@gmail.com'],
            [
                'name' => 'Mahmoud Ghozzi',
                'email' => 'eng.m.yossry@gmail.com',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role_id' => $adminRole->id,
            ]
        );

        // Create Employee
        User::updateOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'email' => 'employee@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role_id' => $employeeRole->id,
            ]
        );

        // Create Accountant
        User::updateOrCreate(
            ['email' => 'accountant@example.com'],
            [
                'name' => 'Accountant User',
                'email' => 'accountant@example.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'role_id' => $accountantRole->id,
            ]
        );

        // Create additional random users
        User::factory()
            ->count(6)
            ->create([
                'role_id' => $employeeRole->id,
            ]);
    }
}
