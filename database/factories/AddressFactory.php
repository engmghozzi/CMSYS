<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
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

        return [
            'client_id' => $clientId,
            'area' => $this->faker->city,
            'block' => $this->faker->randomElement,
            'street' => $this->faker->streetName,
            'house_num' => $this->faker->buildingNumber,
            'floor_num' => $this->faker->numberBetween(1, 10),
            'flat_num' => $this->faker->numberBetween(1, 50),
            'paci_num' => $this->faker->optional()->numberBetween(100000, 999999),
            'address_notes' => $this->faker->optional()->sentence,
            'created_by' => $creator_user,
            'updated_by' => $updator_user,

        ];
    }
}
