<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredTier;
use VueFileManager\Subscription\Domain\Transactions\Models\Usage;

class UsageFactory extends Factory
{
    protected $model = Usage::class;

    public function definition(): array
    {
        return [
            'metered_feature_id' => $this->faker->uuid,
            'subscription_id'      => $this->faker->uuid,
            'quantity'             => random_int(1, 20),
            'created_at'           => $this->faker->dateTimeBetween('-36 months'),
            'updated_at'           => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
