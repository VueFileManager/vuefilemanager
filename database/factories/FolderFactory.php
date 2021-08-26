<?php

namespace Database\Factories;

use Domain\Folders\Models\Folder;
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
            'id'          => $this->faker->uuid,
            'user_id'     => $this->faker->uuid,
            'name'        => $this->faker->word,
            'team_folder' => false,
            'author'      => $this->faker->randomElement(
                ['user', 'member', 'visitor']
            ),
            'created_at'  => $this->faker->dateTimeBetween(
                $startDate = '-36 months',
                $endDate = 'now',
                $timezone = null
            ),
        ];
    }
}
