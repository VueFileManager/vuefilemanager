<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\Transactions\Models\Transaction;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'id'         => $this->faker->uuid(),
            'user_id'    => $this->faker->uuid(),
            'type'       => $this->faker->randomElement(['charge', 'credit', 'withdrawal']),
            'status'     => $this->faker->randomElement(['completed', 'error', 'cancelled']),
            'note'  => $this->faker->randomElement(['Basic Pack', 'Professional Pack', 'Business Pack']),
            'currency'   => $this->faker->randomElement(['USD', 'EUR']),
            'amount'     => $this->faker->randomElement([100, 200, 500]),
            'driver'     => $this->faker->randomElement(['paystack', 'paypal', 'stripe']),
            'reference'  => Str::random(12),
            'created_at' => $this->faker->dateTimeBetween('-36 months'),
            'updated_at' => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
