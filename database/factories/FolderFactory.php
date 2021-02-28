<?php

namespace Database\Factories;

use App\Models\Folder;
use Illuminate\Database\Eloquent\Factories\Factory;

class FolderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Folder::class;

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
            'name'       => $this->faker->word,
            'user_scope' => $this->faker->randomElement(
                ['master', 'editor', 'visitor']
            ),
            'created_at' => $this->faker->dateTimeBetween(
                $startDate = '-36 months', $endDate = 'now', $timezone = null
            ),
        ];
    }
}
