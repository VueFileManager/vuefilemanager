<?php

namespace Database\Factories\Oasis;

use App\Models\Oasis\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id'              => $this->faker->uuid,
            'user_id'         => $this->faker->uuid,
            'client_id'       => $this->faker->uuid,
            'invoice_type'    => $this->faker->randomElement(['regular-invoice', 'advance-invoice']),
            'invoice_number'  => $this->faker->numberBetween(2120001, 2120999),
            'variable_number' => $this->faker->numberBetween(2120001, 2120999),
            'currency'        => $this->faker->randomElement(['CZK', 'EUR']),
            'author_name'     => $this->faker->name,
            'client'          => [
                'name'         => $this->faker->company,
                'email'        => $this->faker->email,
                'phone_number' => $this->faker->phoneNumber,
                'address'      => $this->faker->address,
                'city'         => $this->faker->city,
                'postal_code'  => $this->faker->postcode,
                'country'      => $this->faker->randomElement(
                    ['SK', 'CZ', 'DE', 'FR']
                ),
                'ico'          => $this->faker->numberBetween(11111111, 99999999),
                'dic'          => $this->faker->numberBetween(11111111, 99999999),
                'ic_dph'       => 'CZ' . $this->faker->numberBetween(1111111111, 9999999999),
            ],
            'user'            => [
                'name'         => $this->faker->name,
                'address'      => $this->faker->address,
                'state'        => $this->faker->state,
                'city'         => $this->faker->city,
                'postal_code'  => $this->faker->postcode,
                'country'      => $this->faker->randomElement(
                    ['SK', 'CZ', 'DE', 'FR']
                ),
                'phone_number' => $this->faker->phoneNumber,

                'bank_name' => $this->faker->randomElement(['Fio Banka', 'Tatra Banka']),
                'iban'      => $this->faker->iban('CZ'),
                'swift'     => $this->faker->swiftBicNumber,
            ],
            'items'           => [
                [
                    'description' => $this->faker->realText(60),
                    'amount'      => $this->faker->numberBetween(1, 3),
                    'tax_rate'    => 20,
                    'price'       => $this->faker->randomElement([120, 360, 400, 80, 90, 45, 16, 8]),
                ],
                [
                    'description' => $this->faker->realText(60),
                    'amount'      => $this->faker->numberBetween(1, 3),
                    'tax_rate'    => 20,
                    'price'       => $this->faker->randomElement([120, 360, 400, 80, 90, 45, 16, 8]),
                ],
            ],
            'discount_type'   => $this->faker->randomElement(['percent', 'value', null]),
            'delivery_at'      => $this->faker->dateTimeBetween(
                $startDate = '-6 months', $endDate = 'now', $timezone = null
            ),
            'created_at'      => $this->faker->dateTimeBetween(
                $startDate = '-6 months', $endDate = 'now', $timezone = null
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
        return $this->afterCreating(function (Invoice $invoice) {
            if ($invoice->discount_type === 'percent') {
                $invoice->discount_rate = $this->faker->randomElement([2, 5, 10, 15, 20]);
            }

            if ($invoice->discount_type === 'value') {
                $invoice->discount_rate = $this->faker->randomElement([20, 10]);
            }

            $invoice->save();
        });
    }
}
