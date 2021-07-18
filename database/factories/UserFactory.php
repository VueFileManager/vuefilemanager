<?php

namespace Database\Factories;

use Domain\Settings\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'role'              => $this->faker->randomElement(
                ['user', 'admin']
            ),
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => bcrypt('secret'),
            'remember_token'    => Str::random(10),
            'created_at'        => $this->faker->dateTimeBetween(
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
        return $this->afterCreating(function (User $user) {
            $user
                ->settings()
                ->create([
                    'storage_capacity' => $this->faker->randomNumber(1),
                    'name'             => $this->faker->name,
                    'address'          => $this->faker->address,
                    'state'            => $this->faker->state,
                    'city'             => $this->faker->city,
                    'postal_code'      => $this->faker->postcode,
                    'country'          => $this->faker->randomElement(
                        ['SK', 'CZ', 'DE', 'FR']
                    ),
                    'phone_number'     => $this->faker->phoneNumber,
                    'timezone'         => $this->faker->randomElement(
                        ['+1.0', '+2.0', '+3.0']
                    ),
                ]);
        });
    }
}
