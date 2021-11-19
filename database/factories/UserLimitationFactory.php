<?php

namespace Database\Factories;

use App\Users\Models\UserLimitation;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserLimitationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserLimitation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'            => $this->faker->uuid,
            'max_storage_amount' => $this->faker->randomElement([100, 200, 300]),
            'max_team_members'   => $this->faker->randomElement([10, 20, 30]),
        ];
    }
}
