<?php

namespace VueFileManager\Subscription\Database\Factories;

use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'id'            => Str::uuid(),
            'user_id'       => Str::uuid(),
            'plan_id'       => Str::uuid(),
            'type'          => $this->faker->randomElement(['fixed', 'pre-paid', 'auto-renew']),
            'name'          => $this->faker->randomElement(['Starter Pack', 'Professional Pack', 'Elite Pack']),
            'status'        => $this->faker->randomElement(['active', 'cancelled']),
            'trial_ends_at' => $this->faker->dateTimeBetween('-12 months'),
            'ends_at'       => $this->faker->dateTimeBetween('-12 months'),
            'created_at'    => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
