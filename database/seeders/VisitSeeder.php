<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing clients, addresses, contracts, and users
        $clients = \App\Models\Client::all();
        $addresses = \App\Models\Address::all();
        $contracts = \App\Models\Contract::all();
        $users = \App\Models\User::all();

        if ($clients->isEmpty() || $addresses->isEmpty() || $contracts->isEmpty() || $users->isEmpty()) {
            return; // Skip if no related data exists
        }

        $visitTypes = ['proactive', 'maintenance', 'repair', 'installation', 'other'];
        $visitStatuses = ['scheduled', 'completed', 'cancelled'];

        // Create 1500 sample visits
        for ($i = 0; $i < 1500; $i++) {
            $client = $clients->random();
            $address = $addresses->where('client_id', $client->id)->first() ?? $addresses->random();
            $contract = $contracts->where('client_id', $client->id)->first() ?? $contracts->random();
            $technician = $users->random();
            $creator = $users->random();

            \App\Models\Visit::create([
                'client_id' => $client->id,
                'address_id' => $address->id,
                'contract_id' => $contract->id,
                'technician_id' => $technician->id,
                'visit_scheduled_date' => $scheduledDate = now()->addDays(rand(-30, 30)),
                'visit_actual_date' => $scheduledDate->copy()->addDays(rand(0, 7)),
                'visit_type' => $visitTypes[array_rand($visitTypes)],
                'visit_status' => $visitStatuses[array_rand($visitStatuses)],
                'visit_notes' => rand(0, 1) ? 'Sample visit notes for testing purposes.' : null,
                'created_by' => $creator->id,
                'updated_by' => $creator->id,
            ]);
        }
    }
}
