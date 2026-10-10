<?php
namespace Tests\Mocking\Stripe;

use Illuminate\Support\Facades\Http;

class CreateSetupIntentStripeMockClass
{
    public function __invoke()
    {
        return Http::fake([
            'https://api.stripe.com/v1/setup_intents' => Http::response([
                'id'                     => 'seti_1KBhcBB9m4sTKy1q52kDOR9I',
                'object'                 => 'setup_intent',
                'application'            => null,
                'cancellation_reason'    => null,
                'client_secret'          => 'seti_1KBhcBB9m4sTKy1q52kDOR9I_secret_KrQTiN7KGYtLKAjj2BybzRpgUcpizMq',
                'created'                => 1640705367,
                'customer'               => 'cus_KpRxjQhC61rSgd',
                'description'            => null,
                'last_setup_error'       => null,
                'latest_attempt'         => null,
                'livemode'               => false,
                'mandate'                => null,
                'metadata'               => [],
                'next_action'            => null,
                'on_behalf_of'           => null,
                'payment_method'         => null,
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'automatic',
                    ],
                ],
                'payment_method_types'   => [
                    0 => 'card',
                ],
                'single_use_mandate'     => null,
                'status'                 => 'requires_payment_method',
                'usage'                  => 'off_session',
            ]),
        ]);
    }
}
