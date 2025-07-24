<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Machine>
 */
class MachineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $creator_user = User::inRandomOrder()->first()?->id;
        $updator_user = User::inRandomOrder()->first()?->id;
        $clientId= Client::inRandomOrder()->first()?->id;
        $contractId = Contract::inRandomOrder()->first()?->id;
        $AddressId= Address::inRandomOrder()->first()?->id;
        return [
            'client_id' => $clientId,
            'contract_id' => $contractId, 
            'address_id' => $AddressId, 
            'serial_number' => $this->faker->optional()->numberBetween(10000, 99999),
            'type' => $this->faker->randomElement([
                'Package Unit',
                'Split',
                'Cassette',
                'Central Ducted',
                'Floor Standing',
                'Chiller',
                'Wall Mounted',
                'Portable',
                'Window',
                'VRF/VRV',
                'Other',
            ]),
            'brand' => $this->faker->randomElement([
                'LG',
                'Gree',
                'Carrier',
                'Mitsubishi',
                'Panasonic',
                'Daikin',
                'York',
                'Samsung',
                'Toshiba',
                'General',
                'Sharp',
                'Hitachi',
                'Haier',
                'Trane',
                'Midea',
                'Friedrich',
                'Other'
            ]),
            'UOM' => $this->faker->randomElement(['HP','PTU','Other']),
            'capacity' => $this->faker->numberBetween(1, 10),
            'current_efficiency'=>$this->faker->randomElement([5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100]),
            'cost'=> $this->faker->numberBetween(25, 100),
            'assessment'=> $this->faker->optional()->sentence,
            'created_by' => $creator_user,
            'updated_by' => $updator_user,
        ];
    }
}
