<?php

use Illuminate\Database\Seeder;

class PaymentGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Stripe default record
        DB::table('payment_gateways')->insert([
            'logo' => '/assets/images/stripe-logo-thumbnail.png',
            'name'   => 'Stripe',
            'slug'   => 'stripe',
        ]);
    }
}
