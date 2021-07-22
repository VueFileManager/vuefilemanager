<?php

namespace Database\Factories;

use Domain\Zip\Models\Zip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ZipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Zip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'           => $this->faker->uuid,
            'user_id'      => $this->faker->uuid,
            'shared_token' => Str::random(16),
            'basename'     => $this->faker->word,
        ];
    }
}
