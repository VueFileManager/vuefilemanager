<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\Plans\Models\PlanFixedFeature;

class PlanFixedFeatureFactory extends Factory
{
    protected $model = PlanFixedFeature::class;

    public function definition(): array
    {
        return [
            'plan_id' => $this->faker->uuid,
            'key'     => $this->faker->randomElement([
                'max_storage_amount', 'max_team_members', 'max_upload_size'
            ]),
            'value'   => $this->faker->randomElement([5, 10, 50, 100, 200]),
        ];
    }
}
