<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Domain\UploadRequest\Models\UploadRequest;

class UploadRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UploadRequest::class;

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
            'folder_id'  => $this->faker->uuid,
            'email'      => $this->faker->email,
            'name'       => $this->faker->name,
            'notes'      => $this->faker->realText(80),
            'status'     => $this->faker->randomElement(
                ['active', 'filled', 'expired']
            ),
            'created_at' => $this->faker->dateTimeBetween('-1 months'),
        ];
    }
}
