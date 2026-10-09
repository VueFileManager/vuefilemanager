<?php
namespace Tests\Factories;

use Tests\Models\User;
use Illuminate\Support\Str;
use Orchestra\Testbench\Factories\UserFactory as TestbenchUserFactory;

class UserFactory extends TestbenchUserFactory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'   => $this->faker->uuid,
            'role' => $this->faker->randomElement(
                ['user', 'admin']
            ),
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => bcrypt('secret'),
            'remember_token'    => Str::random(10),
            'created_at'        => $this->faker->dateTimeBetween(
                $startDate = '-36 months',
                $endDate = 'now',
                $timezone = null
            ),
        ];
    }
}
