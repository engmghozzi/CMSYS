<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $this->call([
            RoleSeeder::class,
            FeatureSeeder::class,
            RoleFeatureSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            AddressSeeder::class,
            ContractSeeder::class,
            PaymentSeeder::class,
            MachineSeeder::class,
        ]);
    }
}
