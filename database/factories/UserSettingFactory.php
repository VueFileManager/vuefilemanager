<?php

namespace Database\Factories;

use App\Users\Models\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSetting::class;

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
            'first_name'   => $this->faker->name,
            'last_name'    => $this->faker->lastName,
            'address'      => $this->faker->address,
            'state'        => $this->faker->state,
            'city'         => $this->faker->city,
            'postal_code'  => $this->faker->postcode,
            'phone_number' => $this->faker->phoneNumber,
            'color'        => $this->faker->randomElement(
                config('vuefilemanager.colors')
            ),
            'country'      => $this->faker->randomElement(
                ['SK', 'CZ', 'DE', 'FR']
            ),
            'timezone'     => $this->faker->randomElement(
                ['+1.0', '+2.0', '+3.0']
            ),
        ];
    }
}
