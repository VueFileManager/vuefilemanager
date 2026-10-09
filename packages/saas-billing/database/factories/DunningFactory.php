<?php

namespace VueFileManager\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;

class DunningFactory extends Factory
{
    protected $model = Dunning::class;

    public function definition(): array
    {
        return [
            'id'         => $this->faker->uuid,
            'user_id'    => $this->faker->uuid,
            'sequence'   => 2,
            'type'       => $this->faker->randomElement([
                'limit_usage_in_new_accounts', 'usage_bigger_than_balance'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
