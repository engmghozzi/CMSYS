<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Address;
use App\Models\Contract;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'address_id' => Address::factory(),
            'contract_id' => Contract::factory(),
            'technician_id' => User::factory(),
            'visit_scheduled_date' => $this->faker->date('Y-m-d', '-1 month', '+1 month'),
            'visit_actual_date' => $this->faker->date('Y-m-d', '-1 month', '+1 month'),
            'visit_type' => $this->faker->randomElement(['proactive', 'maintenance', 'repair', 'installation', 'other']),
            'visit_status' => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
            'visit_notes' => $this->faker->optional()->paragraph(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
