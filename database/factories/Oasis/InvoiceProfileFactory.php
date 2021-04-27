<?php

namespace Database\Factories\Oasis;

use App\Models\Oasis\InvoiceProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'                 => $this->faker->uuid,
            'user_id'            => $this->faker->uuid,
            'company'       => $this->faker->company,
            'logo'               => '',
            'email'              => $this->faker->email,
            'ico'                => rand(11111111, 99999999),
            'dic'                => rand(11111111, 99999999),
            'ic_dph'             => 'SK' . rand(111111111111, 999999999999),
            'registration_notes' => $this->faker->realText(80),
            'author'             => $this->faker->name,
            'stamp'              => '',
            'address'            => $this->faker->address,
            'state'              => $this->faker->state,
            'city'               => $this->faker->city,
            'postal_code'        => $this->faker->postcode,
            'country'            => $this->faker->randomElement([
                'SK', 'CZ', 'DE', 'FR'
            ]),
            'phone'              => $this->faker->phoneNumber,
            'bank'          => $this->faker->randomElement([
                'Fio Banka', 'Tatra Banka'
            ]),
            'iban'               => $this->faker->iban('CZ'),
            'swift'              => $this->faker->swiftBicNumber,
        ];
    }
}