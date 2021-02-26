<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'name'       => $this->faker->word,
            'basename'   => Str::slug($this->faker->name),
            'mimetype'   => $this->faker->mimeType,
            'filesize'   => $this->faker->numberBetween(10000, 99999),
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
