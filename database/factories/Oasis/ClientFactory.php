<?php

namespace Database\Factories\Oasis;

use App\Models\Oasis\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'      => $this->faker->uuid,
            'user_id' => $this->faker->uuid,
            'name' => $this->faker->company,
            'email'        => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'address'     => $this->faker->address,
            'city'        => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'country'     => $this->faker->randomElement(
                ['SK', 'CZ', 'DE', 'FR']
            ),
            'ico'    => $this->faker->numberBetween(11111111, 99999999),
            'dic'    => $this->faker->numberBetween(11111111, 99999999),
            'ic_dph' => 'CZ' . $this->faker->numberBetween(1111111111, 9999999999),
            'created_at'        => $this->faker->dateTimeBetween(
                $startDate = '-36 months', $endDate = 'now', $timezone = null
            ),
        ];
    }
}
