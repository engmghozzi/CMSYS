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

        // Create Default Super Admin (Mahmoud Yossry)
        $defaultSuperAdmin = User::updateOrCreate(
            ['email' => 'eng.m.yossry@gmail.com'],
            [
                'name' => 'Eng. Mahmoud Yossry',
                'email' => 'eng.m.yossry@gmail.com',
                'password' => bcrypt('P@$$w0rd'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Super Admin Mahmoud Ghozzi
        User::updateOrCreate(
            ['email' => 'mahmoud@aliandothman.com.kw'],
            [
                'name' => 'Mahmoud Ghozzi',
                'email' => 'mahmoud@aliandothman.com.kw',
                'password' => bcrypt('P@$$w0rd'),
                'is_active' => true,
                'role_id' => $superAdminRole->id,
            ]
        );

        // Create Employee Mohamed
        User::updateOrCreate(
            ['email' => 'mohamed@aliandothman.com.kw'],
            [
                'name' => 'Mohamed Zaki',
                'email' => 'mohamed@aliandothman.com.kw',
                'password' => bcrypt('61668763m'),
                'is_active' => true,
                'role_id' => $employeeRole->id,
            ]
        );
       

    }
}
