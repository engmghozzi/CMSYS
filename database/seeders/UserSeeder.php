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
        $viewerRole = Role::where('name', 'viewer')->first();

        // Create Default Super Admin (Mahmoud Ghozzi)
        $defaultSuperAdmin = User::updateOrCreate(
            ['email' => 'mahmoud@aliandothman.com.kw'],
            [
                'name' => 'Eng. Mahmoud Yossry',
                'email' => 'eng.m.yossry@gmail.com',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Super Admin
        User::updateOrCreate(
            ['email' => 'mahmoud@aliandothman.com.kw'],
            [
                'name' => 'Mahmoud Ghozzi',
                'email' => 'mahmoud@aliandothman.com.kw',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Admin
        User::updateOrCreate(
            ['email' => 'salah@aliandothman.com.kw'],
            [
                'name' => 'Salah Salem',
                'email' => 'salah@aliandothman.com.kw',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Employee
        User::updateOrCreate(
            ['email' => 'hosni@aliandothman.com.kw'],
            [
                'name' => 'Hosni',
                'email' => 'hosni@aliandothman.com.kw',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $employeeRole->id,
            ]
        );

        // Create Accountant
        User::updateOrCreate(
            ['email' => 'mohamed@aliandothman.com.kw'],
            [
                'name' => 'Mohamed Zaki',
                'email' => 'mohamed@aliandothman.com.kw',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $accountantRole->id,
            ]
        );
        // Create Viewer
        User::updateOrCreate(
            ['email' => 'rakan@aliandothman.com.kw'],
            [
                'name' => 'Rakan',
                'email' => 'rakan@aliandothman.com.kw',
                'password' => bcrypt('0000000000'),
                'is_active' => true,
                'role_id' => $viewerRole->id,
            ]
        );

    }
}
