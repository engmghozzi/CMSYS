<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // In ContractSeeder.php
        $availableAddresses = Address::whereDoesntHave('contract')->get();

        foreach ($availableAddresses as $address) {
            Contract::factory()->create([
                'address_id' => $address->id,
                'client_id' => $address->client_id
                // You can override any other fields if needed
            ]);
        }

    }
}
