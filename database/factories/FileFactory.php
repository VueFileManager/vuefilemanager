<?php

namespace Database\Factories;

use App\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

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
            'name'       => $this->faker->name,
            'type'       => $this->faker->randomElement(
                ['image', 'file', 'video', 'audio']
            ),
            'user_scope' => $this->faker->randomElement(
                ['master', 'editor', 'visitor']
            ),
            'created_at' => $this->faker->dateTimeBetween(
                $startDate = '-36 months', $endDate = 'now', $timezone = null
            ),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (File $file) {
            // TODO: add fake files
        });
    }
}
