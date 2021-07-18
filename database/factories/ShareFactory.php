<?php

namespace Database\Factories;

use Domain\Sharing\Models\Share;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ShareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Share::class;

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
            'item_id'      => $this->faker->uuid,
            'token'        => Str::random(16),
            'type'         => $this->faker->randomElement(['file', 'folder']),
            'permission'   => $this->faker->randomElement(['visitor', 'editor']),
            'is_protected' => $this->faker->boolean(20),
            'password'     => bcrypt('secret'),
            'expire_in'    => $this->faker->randomElement([1, 6, 12, 24]),
        ];
    }
}
