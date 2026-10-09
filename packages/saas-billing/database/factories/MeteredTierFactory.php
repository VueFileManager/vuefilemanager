<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\Plans\Models\MeteredTier;

class MeteredTierFactory extends Factory
{
    protected $model = MeteredTier::class;

    public function definition(): array
    {
        return [
            'metered_feature_id' => $this->faker->uuid,
            'first_unit'           => 1,
            'last_unit'            => $this->faker->randomElement([10, 50, 100]),
            'per_unit'             => $this->faker->randomElement([0.19, 0.49, 2.00, 3.49]),
            'flat_fee'             => $this->faker->randomElement([0, 5.00, 9.90, 12.49]),
        ];
    }
}
