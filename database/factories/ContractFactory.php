<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()?->id;

        if (!$userId) {
            throw new \Exception("No available users.");
        }

        $start_date = $this->faker->dateTimeBetween('-1 year', 'now');
        $duration_months = $this->faker->numberBetween(12, 36);
        $end_date = (clone $start_date)->modify("+$duration_months months");

        $year = $start_date->format('y');
        $random_number = str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
        $contract_num = "CONT/{$year}/{$random_number}";

        return [
            'contract_num' => $contract_num,
            // 'client_id' and 'address_id' will be passed from seeder
            'type' => $this->faker->randomElement(['L', 'LS', 'C', 'Other']),

            'start_date' => $start_date->format('Y-m-d'),
            'duration_months' => $duration_months,
            'end_date' => $end_date->format('Y-m-d'),
            'total_amount' => $this->faker->randomFloat(0, 300, 1000),
            'paid_amount' => $this->faker->randomFloat(0, 0, 300),
            'remaining_amount' => function (array $attributes) {
                return $attributes['total_amount'] - $attributes['paid_amount'];
             },

            'commission_amount' => $this->faker->optional()->randomFloat(3, 10, 200),
            'commission_type' => $this->faker->optional()->randomElement(['Incentive Bonus', 'Referral Commission', 'Other']),
            'commission_recipient' => $this->faker->optional()->name(),
            'commission_date' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['active', 'expired', 'cancelled']),
            'details' => $this->faker->paragraphs(2, true),
            'attachment_url' => $this->faker->optional(0.7)->randomElement([
                'contracts/contract_' . $this->faker->numberBetween(1, 100) . '.pdf',
                'contracts/agreement_' . $this->faker->numberBetween(1, 100) . '.pdf',
                'contracts/document_' . $this->faker->numberBetween(1, 100) . '.pdf',
            ]),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }


}

