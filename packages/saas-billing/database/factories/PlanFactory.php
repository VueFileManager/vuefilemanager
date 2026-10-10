<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Support\Str;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'id'          => $this->faker->uuid,
            'type'        => 'fixed',
            'name'        => $this->faker->randomElement(['Basic', 'Professional', 'Business']) . ' Pack - ' . Str::random(8),
            'description' => $this->faker->realText(40),
            'currency'    => $this->faker->randomElement(['USD', 'EUR']),
            'amount'      => $this->faker->randomElement([29.90, 49, 99.99]),
            'interval'    => $this->faker->randomElement(['day', 'week', 'month', 'year']),
            'visible'     => 1,
            'created_at'  => $this->faker->dateTimeBetween('-36 months'),
            'updated_at'  => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
