<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;

class CreditCardFactory extends Factory
{
    protected $model = CreditCard::class;

    public function definition(): array
    {
        return [
            'id'         => $this->faker->uuid,
            'user_id'    => $this->faker->uuid,
            'last4'      => random_int(1111, 9999),
            'brand'      => $this->faker->randomElement(['visa', 'mastercard']),
            'reference'  => 'pm_' . random_int(11111111, 99999999),
            'service'    => $this->faker->randomElement(['stripe']),
            'expiration' => $this->faker->dateTimeBetween('now', '+6 months'),
            'created_at' => $this->faker->dateTimeBetween('-36 months'),
            'updated_at' => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
