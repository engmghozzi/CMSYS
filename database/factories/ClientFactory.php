<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $creator_user = User::inRandomOrder()->first()?->id ?? 1;
        $updator_user = User::inRandomOrder()->first()?->id ?? 1;

        return [
            'name'=>$this->faker->name(),
            'mobile_number' => $this->faker->phoneNumber(),
            'alternate_mobile_number' => $this->faker->phoneNumber(),
            'client_type' => $this->faker->randomElement(['Client','Company','Contractor','Other']),
            'created_by'=>$creator_user,
            'updated_by'=>$updator_user

        ];
    }
}
