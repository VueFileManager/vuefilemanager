<?php

namespace Database\Factories;

use App\Users\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'                => Str::uuid(),
            'role'              => $this->faker->randomElement(
                ['user', 'admin']
            ),
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => bcrypt('secret'),
            'remember_token'    => Str::random(10),
            'created_at'        => $this->faker->dateTimeBetween('-36 months'),
        ];
    }
}
