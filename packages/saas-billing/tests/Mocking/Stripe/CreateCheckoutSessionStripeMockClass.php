<?php
namespace Tests\Mocking\Stripe;

use Illuminate\Support\Facades\Http;

class CreateCheckoutSessionStripeMockClass
{
    public function __invoke()
    {
        return Http::fake([
            'https://api.stripe.com/v1/checkout/sessions' => Http::response([
                'id'                          => 'cs_test_a1KLRKWfnXzfewSF8YkiXapgsO4RtNOxMX0FZcyixI0tPQvmDZr4KxAq2Y',
                'object'                      => 'checkout.session',
                'after_expiration'            => null,
                'allow_promotion_codes'       => null,
                'amount_subtotal'             => 5999,
                'amount_total'                => 5999,
                'automatic_tax'               => [
                    'enabled' => false,
                    'status'  => null,
                ],
                'billing_address_collection'  => null,
                'cancel_url'                  => 'http://localhost/platform/files',
                'client_reference_id'         => null,
                'consent'                     => null,
                'consent_collection'          => null,
                'currency'                    => 'usd',
                'customer'                    => 'cus_Khhqv9DY6DPxyy',
                'customer_details'            => [
                    'email'      => 'arno29@example.com',
                    'phone'      => null,
                    'tax_exempt' => null,
                    'tax_ids'    => null,
                ],
                'customer_email'              => null,
                'expires_at'                  => 1638550037,
                'livemode'                    => false,
                'locale'                      => null,
                'metadata'                    => [],
                'mode'                        => 'subscription',
                'payment_intent'              => null,
                'payment_method_options'      => null,
                'payment_method_types'        => [
                    0 => 'card',
                ],
                'payment_status'              => 'unpaid',
                'phone_number_collection'     => [
                    'enabled' => false,
                ],
                'recovered_from'              => null,
                'setup_intent'                => null,
                'shipping'                    => null,
                'shipping_address_collection' => null,
                'shipping_options'            => [],
                'shipping_rate'               => null,
                'status'                      => 'open',
                'submit_type'                 => null,
                'subscription'                => null,
                'success_url'                 => 'http://localhost/platform/files',
                'total_details'               => [
                    'amount_discount' => 0,
                    'amount_shipping' => 0,
                    'amount_tax'      => 0,
                ],
                'url'                         => 'https://checkout.stripe.com/pay/cs_test_a1KLRKWfnXzfewSF8Yk',
            ]),
        ]);
    }
}
