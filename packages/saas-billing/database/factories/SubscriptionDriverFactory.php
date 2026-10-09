<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use VueFileManager\Subscription\Domain\Subscriptions\Models\SubscriptionDriver;

class SubscriptionDriverFactory extends Factory
{
    protected $model = SubscriptionDriver::class;

    public function definition(): array
    {
        return [
            'subscription_id'        => $this->faker->uuid,
            'driver_subscription_id' => Str::random(),
            'driver'                 => $this->faker->randomElement(['paypal', 'paystack', 'stripe']),
        ];
    }
}
