<?php

namespace VueFileManager\Subscription\Database\Factories;

use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id'        => Str::uuid(),
            'driver'         => $this->faker->randomElement(getActiveDrivers()),
            'driver_user_id' => 'CUS_' . Str::random(15),
            'created_at'     => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
