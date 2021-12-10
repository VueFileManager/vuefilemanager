<?php

namespace Database\Factories;

use Domain\Sharing\Models\Share;
use Domain\Traffic\Models\Traffic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrafficFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Traffic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'         => $this->faker->uuid,
            'user_id'    => $this->faker->uuid,
            'upload'     => rand(11111111, 99999999),
            'download'   => rand(11111111, 99999999),
            'created_at' => $this->faker->dateTimeBetween('-month'),
        ];
    }
}
