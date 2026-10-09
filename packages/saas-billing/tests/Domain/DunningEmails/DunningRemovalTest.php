<?php
namespace Tests\Domain\DunningEmails;

use Tests\TestCase;
use Tests\Models\User;
use Tests\Helpers\StripeTestHelpers;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Domain\DunningEmails\Models\Dunning;

class DunningRemovalTest extends TestCase
{
    use StripeTestHelpers;

    /**
     * @test
     */
    public function it_remove_dunning_after_paystack_charge_complete()
    {
        $user = User::factory()
            ->create([
                'email' => 'howdy@hi5ve.digital',
            ]);

        Dunning::factory()
            ->createOneQuietly([
                'user_id'  => $user->id,
                'type'     => 'usage_bigger_than_balance',
                'sequence' => 3,
            ]);

        $payload = [
            'event'         => 'charge.success',
            'data'          => [
                'id'                   => 1454595684,
                'domain'               => 'test',
                'status'               => 'success',
                'reference'            => 'gD2au4Jyuc',
                'amount'               => 1000,
                'message'              => null,
                'gateway_response'     => 'Successful',
                'paid_at'              => '2021-11-18T06:42:13.000Z',
                'created_at'           => '2021-11-18T06:42:08.000Z',
                'channel'              => 'card',
                'currency'             => 'ZAR',
                'ip_address'           => '88.212.40.149',
                'metadata'             => [
                    'referrer' => 'http://localhost:8000/user/profile',
                ],
                'log'                  => [
                    'start_time' => 1637217729,
                    'time_spent' => 4,
                    'attempts'   => 1,
                    'errors'     => 0,
                    'success'    => true,
                    'mobile'     => false,
                    'input'      => [],
                    'history'    => [
                        [
                            'type'    => 'action',
                            'message' => 'Attempted to pay with card',
                            'time'    => 4,
                        ],
                        [
                            'type'    => 'success',
                            'message' => 'Successfully paid with card',
                            'time'    => 4,
                        ],
                    ],
                ],
                'fees'                 => 129,
                'fees_split'           => null,
                'authorization'        => [
                    'authorization_code'           => 'AUTH_794qfjtskk',
                    'bin'                          => '408408',
                    'last4'                        => '4081',
                    'exp_month'                    => '12',
                    'exp_year'                     => '2030',
                    'channel'                      => 'card',
                    'card_type'                    => 'visa',
                    'bank'                         => 'TEST BANK',
                    'country_code'                 => 'ZA',
                    'brand'                        => 'visa',
                    'reusable'                     => true,
                    'signature'                    => 'SIG_XtQmL8nBieEY6wPi0zzP',
                    'account_name'                 => null,
                    'receiver_bank_account_number' => null,
                    'receiver_bank'                => null, ],
                'customer'             => [
                    'id'                         => 61682919,
                    'first_name'                 => null,
                    'last_name'                  => null,
                    'email'                      => 'howdy@hi5ve.digital',
                    'customer_code'              => 'CUS_eb0h9lcy7ustcfw',
                    'phone'                      => null,
                    'metadata'                   => null,
                    'risk_action'                => 'default',
                    'international_format_phone' => null,
                ],
                'plan'                 => [],
                'subaccount'           => [],
                'split'                => [],
                'order_id'             => null,
                'paidAt'               => '2021-11-18T06:42:13.000Z',
                'requested_amount'     => 1000,
                'pos_transaction_data' => null,
                'source'               => [
                    'source'     => 'checkout',
                    'identifier' => null,
                    'event_type' => 'web',
                ],
                'fees_breakdown'       => null,
            ],
            'order'         => null,
            'business_name' => 'VueFileManager',
        ];

        // Create signature
        $hash = hash_hmac('sha512', json_encode($payload), config('subscription.credentials.paystack.secret'));

        $this
            ->withHeader('x-paystack-signature', $hash)
            ->postJson('/api/subscriptions/paystack/webhooks', $payload)
            ->assertOk();

        $this
            ->assertDatabaseMissing('dunnings', [
                'user_id'  => $user->id,
            ])
            ->assertDatabaseHas('balances', [
                'user_id'  => $user->id,
                'amount'   => 10.00,
                'currency' => 'ZAR',
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'currency'  => 'ZAR',
                'amount'    => 10,
                'driver'    => 'paystack',
                'reference' => 'gD2au4Jyuc',
            ]);
    }

    /**
     * @test
     */
    public function it_remove_dunning_after_paypal_charge_complete()
    {
        $user = User::factory()
            ->create([
                'email' => 'howdy@hi5ve.digital',
            ]);

        Dunning::factory()
            ->createOneQuietly([
                'user_id'  => $user->id,
                'type'     => 'usage_bigger_than_balance',
                'sequence' => 3,
            ]);

        $this->postJson('/api/subscriptions/paypal/webhooks', [
            'id'               => 'WH-59T06126UF194714E-4G3970566J739735U',
            'event_version'    => '1.0',
            'create_time'      => '2022-03-04T16:10:12.392Z',
            'resource_type'    => 'checkout-order',
            'resource_version' => '2.0',
            'event_type'       => 'CHECKOUT.ORDER.APPROVED',
            'summary'          => 'An order has been approved by buyer',
            'resource'         => [
                'create_time'    => '2022-03-04T16:09:55Z',
                'purchase_units' => [
                    [
                        'reference_id' => 'default',
                        'amount'       =>
                            [
                                'currency_code' => 'USD',
                                'value'         => '10.00',
                            ],
                        'payee'        =>
                            [
                                'email_address' => 'sb-i2vj711567306@business.example.com',
                                'merchant_id'   => 'LV8KBHAFXJMPJ',
                            ],
                        'custom_id'    => $user->id,
                        'shipping'     =>
                            [
                                'name'    =>
                                    [
                                        'full_name' => 'John Doe',
                                    ],
                                'address' =>
                                    [
                                        'address_line_1' => '1 Main St',
                                        'admin_area_2'   => 'San Jose',
                                        'admin_area_1'   => 'CA',
                                        'postal_code'    => '95131',
                                        'country_code'   => 'US',
                                    ],
                            ],
                    ],
                ],
                'links'          =>
                    [
                        0 =>
                            [
                                'href'   => 'https://api.sandbox.paypal.com/v2/checkout/orders/32649052UT384661G',
                                'rel'    => 'self',
                                'method' => 'GET',
                            ],
                        1 =>
                            [
                                'href'   => 'https://api.sandbox.paypal.com/v2/checkout/orders/32649052UT384661G',
                                'rel'    => 'update',
                                'method' => 'PATCH',
                            ],
                        2 =>
                            [
                                'href'   => 'https://api.sandbox.paypal.com/v2/checkout/orders/32649052UT384661G/capture',
                                'rel'    => 'capture',
                                'method' => 'POST',
                            ],
                    ],
                'id'             => '32649052UT384661G',
                'intent'         => 'CAPTURE',
                'payer'          =>
                    [
                        'name'          =>
                            [
                                'given_name' => 'John',
                                'surname'    => 'Doe',
                            ],
                        'email_address' => 'ernest@azet.sk',
                        'payer_id'      => 'XEBW65LBRMPMA',
                        'address'       =>
                            [
                                'country_code' => 'US',
                            ],
                    ],
                'status'         => 'APPROVED',
            ],
            'links'            => [
                0 =>
                    [
                        'href'   => 'https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-59T06126UF194714E-4G3970566J739735U',
                        'rel'    => 'self',
                        'method' => 'GET',
                    ],
                1 =>
                    [
                        'href'   => 'https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-59T06126UF194714E-4G3970566J739735U/resend',
                        'rel'    => 'resend',
                        'method' => 'POST',
                    ],
            ],
        ])->assertOk();

        $this
            ->assertDatabaseMissing('dunnings', [
                'user_id'  => $user->id,
            ])
            ->assertDatabaseHas('balances', [
                'user_id'  => $user->id,
                'amount'   => 10.00,
                'currency' => 'USD',
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'currency'  => 'USD',
                'amount'    => 10,
                'driver'    => 'paypal',
                'reference' => '32649052UT384661G',
            ]);
    }

    /**
     * @test
     */
    public function it_remove_dunning_after_stripe_payment_card_was_added()
    {
        $user = User::factory()
            ->create([
                'email' => 'howdy@hi5ve.digital',
            ]);

        Customer::create([
            'user_id'        => $user->id,
            'driver_user_id' => 'cus_KrgRc2TH3yh3xC',
            'driver'         => 'stripe',
        ]);

        Dunning::factory()
            ->createOneQuietly([
                'user_id'  => $user->id,
                'type'     => 'limit_usage_in_new_accounts',
                'sequence' => 3,
            ]);

        $payload = [
            'created'          => 1326853478,
            'livemode'         => false,
            'id'               => 'evt_00000000000000',
            'type'             => 'payment_method.attached',
            'object'           => 'event',
            'request'          => null,
            'pending_webhooks' => 1,
            'api_version'      => '2020-08-27',
            'data'             => [
                'object' => [
                    'id'              => 'pm_00000000000000',
                    'object'          => 'payment_method',
                    'billing_details' => [
                        'address' => [
                            'city'        => null,
                            'country'     => null,
                            'line1'       => null,
                            'line2'       => null,
                            'postal_code' => '94107',
                            'state'       => null,
                        ],
                        'email'   => 'jenny@example.com',
                        'name'    => null,
                        'phone'   => '+15555555555',
                    ],
                    'card'            => [
                        'brand'                => 'visa',
                        'checks'               => [
                            'address_line1_check'       => null,
                            'address_postal_code_check' => null,
                            'cvc_check'                 => 'pass',
                        ],
                        'country'              => 'US',
                        'exp_month'            => 8,
                        'exp_year'             => 2022,
                        'fingerprint'          => 'rQCgh6fjRkVkJKgr',
                        'funding'              => 'credit',
                        'generated_from'       => null,
                        'last4'                => '4242',
                        'networks'             => [
                            'available' => [
                                'visa',
                            ],
                            'preferred' => null,
                        ],
                        'three_d_secure_usage' => [
                            'supported' => true,
                        ],
                        'wallet'               => null,
                    ],
                    'created'         => 123456789,
                    'customer'        => 'cus_KrgRc2TH3yh3xC',
                    'livemode'        => false,
                    'metadata'        => [
                        'order_id' => '123456789',
                    ],
                    'type'            => 'card',
                ],
            ],
        ];

        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        $this
            ->assertDatabaseMissing('dunnings', [
                'user_id'  => $user->id,
            ]);
    }
}
