<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\FailedPayments\Models\FailedPayment;

class FailedPaymentFactory extends Factory
{
    protected $model = FailedPayment::class;

    public function definition(): array
    {
        return [
            'id'             => $this->faker->uuid,
            'user_id'        => $this->faker->uuid,
            'amount'         => $this->faker->randomElement([12.23, 26.20, 31.39]),
            'currency'       => $this->faker->randomElement(['USD', 'EUR']),
            'source'         => $this->faker->randomElement(['balance', 'credit-card']),
            'note'           => $this->faker->text(40),
            'created_at'     => $this->faker->dateTimeBetween('-36 months'),
            'updated_at'     => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
