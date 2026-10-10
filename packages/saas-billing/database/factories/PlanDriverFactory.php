<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use VueFileManager\Subscription\Domain\Plans\Models\PlanDriver;
use VueFileManager\Subscription\Domain\Plans\Models\PlanFixedItem;

class PlanDriverFactory extends Factory
{
    protected $model = PlanDriver::class;

    public function definition(): array
    {
        return [
            'plan_id'        => $this->faker->uuid,
            'driver_plan_id' => Str::random(),
            'driver'         => $this->faker->randomElement(['paypal', 'paystack', 'stripe']),
        ];
    }
}
