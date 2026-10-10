<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\Credits\Models\Balance;

class BalanceFactory extends Factory
{
    protected $model = Balance::class;

    public function definition(): array
    {
        return [
            'id'         => $this->faker->uuid,
            'user_id'    => $this->faker->uuid,
            'currency'   => $this->faker->randomElement(['USD', 'EUR']),
            'amount'    => $this->faker->randomElement([100, 200, 500]),
            'created_at' => $this->faker->dateTimeBetween('-36 months'),
            'updated_at' => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
