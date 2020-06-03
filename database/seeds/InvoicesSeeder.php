<?php

use Illuminate\Database\Seeder;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::find(1);
        $seller = [
            'settings' => [
                'billing_name'         => 'VueFileManager',
                'billing_address'      => 'Somewhere 32',
                'billing_state'        => 'Washington',
                'billing_city'         => 'Manchester',
                'billing_postal_code'  => '04001',
                'billing_country'      => 'The USA',
                'billing_phone_number' => '490321-6354774',
                'billing_vat_number'   => '7354724626246',
            ]
        ];

        $invoice = \App\Invoice::create([
            'token'    => \Illuminate\Support\Str::random(),
            'order'    => '200001',
            'user_id'  => 1,
            'plan_id'  => 1,
            'seller'   => [
                'billing_name'         => $seller['settings']['billing_name'],
                'billing_address'      => $seller['settings']['billing_address'],
                'billing_state'        => $seller['settings']['billing_state'],
                'billing_city'         => $seller['settings']['billing_city'],
                'billing_postal_code'  => $seller['settings']['billing_postal_code'],
                'billing_country'      => $seller['settings']['billing_country'],
                'billing_phone_number' => $seller['settings']['billing_phone_number'],
                'billing_vat_number'   => $seller['settings']['billing_vat_number'],
            ],
            'client'   => [
                'billing_name'         => $user->settings->billing_name,
                'billing_address'      => $user->settings->billing_address,
                'billing_state'        => $user->settings->billing_state,
                'billing_city'         => $user->settings->billing_city,
                'billing_postal_code'  => $user->settings->billing_postal_code,
                'billing_country'      => $user->settings->billing_country,
                'billing_phone_number' => $user->settings->billing_phone_number,
            ],
            'bag'      => [
                [
                    'description' => 'Subscription - Starter Pack',
                    'date'        => '01-05-2020 01-06-2020',
                    'amount'      => 2.99,
                ]
            ],
            'notes'    => '',
            'total'    => 2.99,
            'currency' => 'USD',
            'path'     => '/invoices/200001-8HUrJkNdLKilNMeF.pdf',
        ]);
    }
}
