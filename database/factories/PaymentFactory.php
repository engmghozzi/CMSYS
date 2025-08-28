<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        // Get a client with at least one contract
        $client = Client::has('contracts')->inRandomOrder()->first();

        if (!$client) {
            throw new \Exception('No client with contracts found.');
        }

        // Get a random contract for this client
        $contract = $client->contracts()->inRandomOrder()->first();

        // Get a random user for tracking
        $userId = User::inRandomOrder()->first()->id;

        // Generate payment and due dates
        $paymentDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $dueDate = (clone $paymentDate)->modify('+10 days');

        return [
            'client_id' => $client->id,
            'contract_id' => $contract->id,
            'payment_date' => $paymentDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'amount' => $this->faker->randomFloat(0, 50, 2000),
            'status' => $this->faker->randomElement([
                'Unpaid',
                'Paid',
                'Other'
            ]),
            
            'method' => $this->faker->randomElement([
                'Cash',
                'KNET',
                'Cheque',
                'Wamd',
                'other'
            ]),
            'notes' => $this->faker->optional()->sentence(),
            'created_by' => $userId,
            'updated_by' => $userId,
        ];
    }
}
