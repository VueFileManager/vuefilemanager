<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\Plans\Models\PlanMeteredFeature;

class PlanMeteredFeatureFactory extends Factory
{
    protected $model = PlanMeteredFeature::class;

    public function definition(): array
    {
        return [
            'id'        => $this->faker->uuid,
            'plan_id'   => $this->faker->uuid,
            'aggregate_strategy' => $this->faker->randomElement(['sum_of_usage', 'maximum_usage']),
            'key'       => $this->faker->randomElement(['bandwidth', 'storage', 'members']),
        ];
    }
}
