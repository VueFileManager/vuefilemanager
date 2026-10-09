<?php
namespace Tests\Support\Webhooks;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCancelled;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Subscriptions\Notifications\SubscriptionWasCreatedNotification;

class PayStackWebhooksTest extends TestCase
{
    /**
     * @test
     */
    public function paystack_webhook_create_subscription()
    {
        Event::fake([
            SubscriptionWasCreated::class,
        ]);

        $user = User::factory()
            ->create();

        // Create plan with features
        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'paystack',
            ])
            ->hasFixedFeatures(2)
            ->create();

        $payload = [
            'event' => 'subscription.create',
            'data'  => [
                'domain'            => 'test',
                'status'            => 'active',
                'subscription_code' => 'SUB_vsyqdmlzble3uii',
                'amount'            => 50000,
                'cron_expression'   => '0 0 28 * *',
                'next_payment_date' => now()->addDays(28),
                'open_invoice'      => null,
                'createdAt'         => '2016-03-20T00:23:24.000Z',
                'plan'              => [
                    'name'          => 'Monthly retainer',
                    'plan_code'     => $plan->driverId('paystack'),
                    'description'   => null,
                    'amount'        => $plan->amount,
                    'interval'      => $plan->interval,
                    'send_invoices' => true,
                    'send_sms'      => true,
                    'currency'      => 'NGN',
                ],
                'authorization'     => [
                    'authorization_code' => 'AUTH_96xphygz',
                    'bin'                => '539983',
                    'last4'              => '7357',
                    'exp_month'          => '10',
                    'exp_year'           => '2017',
                    'card_type'          => 'MASTERCARD DEBIT',
                    'bank'               => 'GTBANK',
                    'country_code'       => 'NG',
                    'brand'              => 'MASTERCARD',
                    'account_name'       => 'BoJack Horseman',
                ],
                'customer'          => [
                    'first_name'    => 'BoJack',
                    'last_name'     => 'Horseman',
                    'email'         => $user->email,
                    'customer_code' => 'CUS_xnxdt6s1zg1f4nx',
                    'phone'         => '',
                    'metadata'      => [
                    ],
                    'risk_action'   => 'default',
                ],
                'created_at'        => '2016-10-01T10:59:59.000Z',
            ],
        ];

        // Create signature
        $hash = hash_hmac('sha512', json_encode($payload), config('subscription.credentials.paystack.secret'));

        // Send webhook
        $this
            ->withHeader('x-paystack-signature', $hash)
            ->postJson('/api/subscriptions/paystack/webhooks', $payload)
            ->assertOk();

        // Check if subscription was created
        $subscription = Subscription::first();

        // Check relationships are correct
        $this->assertEquals($user->id, $subscription->user->id);
        $this->assertEquals($plan->id, $subscription->plan->id);

        $this->assertDatabaseHas('subscription_drivers', [
            'driver_subscription_id' => 'SUB_vsyqdmlzble3uii',
        ]);

        $this->assertDatabaseHas('customers', [
            'driver_user_id' => 'CUS_xnxdt6s1zg1f4nx',
            'user_id'        => $user->id,
            'driver'         => 'paystack',
        ]);

        Notification::assertSentTo($user, SubscriptionWasCreatedNotification::class);

        Event::assertDispatched(fn (SubscriptionWasCreated $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function paystack_webhook_replace_subscription()
    {
        Event::fake([
            SubscriptionWasUpdated::class,
        ]);

        $user = User::factory()
            ->create();

        // Create customer
        $customer = Customer::factory()
            ->create([
                'user_id'        => $user->id,
                'driver_user_id' => 'CUS_xnxdt6s1zg1f4nx',
                'driver'         => 'paystack',
            ]);

        // Create plan with features
        [$plan, $planHigher] = Plan::factory()
            ->hasDrivers([
                'driver' => 'paystack',
            ])
            ->count(2)
            ->create();

        $initialSubscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paystack',
            ])
            ->create([
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'status'  => 'active',
                'name'    => $plan->name,
            ]);

        $newSubscriptionCode = 'SUB_mkflwaenlrfsdsd';

        Http::fake([
            "https://api.paystack.co/subscription/{$initialSubscription->driverId()}" => Http::response(
                [
                    'status'  => true,
                    'message' => 'Subscription retrieved successfully',
                    'data'    => [
                        'invoices'          => [],
                        'customer'          => [
                            'first_name'    => 'BoJack',
                            'last_name'     => 'Horseman',
                            'email'         => 'bojack@horsinaround.com',
                            'phone'         => null,
                            'metadata'      => [
                                'photos' => [
                                    [
                                        'type'      => 'twitter',
                                        'typeId'    => 'twitter',
                                        'typeName'  => 'Twitter',
                                        'url'       => 'https://d2ojpxxtu63wzl.cloudfront.net/static/61b1a0a1d4dda2c9fe9e165fed07f812_a722ae7148870cc2e33465d1807dfdc6efca33ad2c4e1f8943a79eead3c21311',
                                        'isPrimary' => false,
                                    ],
                                ],
                            ],
                            'domain'        => 'test',
                            'customer_code' => 'CUS_xnxdt6s1zg1f4nx',
                            'id'            => 1173,
                            'integration'   => 100032,
                            'createdAt'     => '2016-03-29T20:03:09.000Z',
                            'updatedAt'     => '2016-03-29T20:53:05.000Z',
                        ],
                        'plan'              => [
                            'domain'              => 'test',
                            'name'                => 'Monthly retainer (renamed)',
                            'plan_code'           => 'PLN_gx2wn530m0i3w3m',
                            'description'         => null,
                            'amount'              => 50000,
                            'interval'            => 'monthly',
                            'send_invoices'       => true,
                            'send_sms'            => true,
                            'hosted_page'         => false,
                            'hosted_page_url'     => null,
                            'hosted_page_summary' => null,
                            'currency'            => 'NGN',
                            'id'                  => 28,
                            'integration'         => 100032,
                            'createdAt'           => '2016-03-29T22:42:50.000Z',
                            'updatedAt'           => '2016-03-29T23:51:41.000Z',
                        ],
                        'integration'       => 100032,
                        'authorization'     => [
                            'authorization_code' => 'AUTH_6tmt288t0o',
                            'bin'                => '408408',
                            'last4'              => '4081',
                            'exp_month'          => '12',
                            'exp_year'           => '2020',
                            'channel'            => 'card',
                            'card_type'          => 'visa visa',
                            'bank'               => 'TEST BANK',
                            'country_code'       => 'NG',
                            'brand'              => 'visa',
                            'reusable'           => true,
                            'signature'          => 'SIG_uSYN4fv1adlAuoij8QXh',
                            'account_name'       => 'BoJack Horseman',
                        ],
                        'domain'            => 'test',
                        'start'             => 1459296064,
                        'status'            => 'active',
                        'quantity'          => 1,
                        'amount'            => 50000,
                        'subscription_code' => $newSubscriptionCode,
                        'email_token'       => 'd7gofp6yppn3qz7',
                        'easy_cron_id'      => null,
                        'cron_expression'   => '0 0 28 * *',
                        'next_payment_date' => '2016-04-28T07:00:00.000Z',
                        'open_invoice'      => null,
                        'id'                => 9,
                        'createdAt'         => '2016-03-30T00:01:04.000Z',
                        'updatedAt'         => '2016-03-30T00:22:58.000Z',
                    ],
                ]
            ),
            'https://api.paystack.co/subscription/disable'                            => Http::response([
                'status'  => true,
                'message' => 'Subscription disabled successfully',
            ]),
        ]);

        $payload = [
            'event' => 'subscription.create',
            'data'  => [
                'domain'            => 'test',
                'status'            => 'active',
                'subscription_code' => $newSubscriptionCode,
                'amount'            => 50000,
                'cron_expression'   => '0 0 28 * *',
                'next_payment_date' => now()->addDays(28),
                'open_invoice'      => null,
                'createdAt'         => '2016-03-20T00:23:24.000Z',
                'plan'              => [
                    'name'          => 'Monthly retainer',
                    'plan_code'     => $planHigher->driverId('paystack'),
                    'description'   => null,
                    'amount'        => $plan->amount,
                    'interval'      => $plan->interval,
                    'send_invoices' => true,
                    'send_sms'      => true,
                    'currency'      => 'NGN',
                ],
                'authorization'     => [
                    'authorization_code' => 'AUTH_96xphygz',
                    'bin'                => '539983',
                    'last4'              => '7357',
                    'exp_month'          => '10',
                    'exp_year'           => '2017',
                    'card_type'          => 'MASTERCARD DEBIT',
                    'bank'               => 'GTBANK',
                    'country_code'       => 'NG',
                    'brand'              => 'MASTERCARD',
                    'account_name'       => 'BoJack Horseman',
                ],
                'customer'          => [
                    'first_name'    => 'BoJack',
                    'last_name'     => 'Horseman',
                    'email'         => 'bojack@horsinaround.com',
                    'customer_code' => $customer->driver_user_id,
                    'phone'         => '',
                    'metadata'      => [
                    ],
                    'risk_action'   => 'default',
                ],
                'created_at'        => '2016-10-01T10:59:59.000Z',
            ],
        ];

        // Create signature
        $hash = hash_hmac('sha512', json_encode($payload), config('subscription.credentials.paystack.secret'));

        // Send webhook
        $this
            ->withHeader('x-paystack-signature', $hash)
            ->postJson('/api/subscriptions/paystack/webhooks', $payload)
            ->assertOk();

        $this
            ->assertDatabaseHas('subscription_drivers', [
                'driver_subscription_id' => $newSubscriptionCode,
            ])
            ->assertDatabaseMissing('subscriptions', [
                'plan_id' => $initialSubscription->id,
                'name'    => $initialSubscription->name,
            ])
            ->assertDatabaseHas('subscriptions', [
                'plan_id' => $planHigher->id,
                'name'    => $planHigher->name,
            ]);

        Event::assertDispatched(fn (SubscriptionWasUpdated $event) => $event->subscription->name === $planHigher->name);

        Http::assertSentInOrder([
            fn (Request $request) => $request->url() === "https://api.paystack.co/subscription/{$initialSubscription->driverId()}",
            fn (Request $request) => $request->url() === 'https://api.paystack.co/subscription/disable',
        ]);
    }

    /**
     * @test
     */
    public function paystack_webhook_disabled_subscription()
    {
        Event::fake([
            SubscriptionWasCancelled::class,
        ]);

        $user = User::factory()
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paystack',
            ])
            ->create([
                'user_id'    => $user->id,
                'status'     => 'active',
                'ends_at'    => null,
                'created_at' => now()->subDays(14),
            ]);

        $cancelledAt = now()->addDays(30);

        $payload = [
            'event' => 'subscription.not_renew',
            'data'  => [
                'id'                  => 329496,
                'domain'              => 'test',
                'status'              => 'non-renewing',
                'subscription_code'   => $subscription->driverId(),
                'email_token'         => 'tdnx5c6ce0cj6ax',
                'amount'              => 10000,
                'cron_expression'     => '0 0 15 * *',
                'next_payment_date'   => null,
                'open_invoice'        => null,
                'cancelledAt'         => $cancelledAt,
                'integration'         => 665153,
                'plan'                => [
                    'id'            => 190391,
                    'name'          => 'Professional Pack - tTI8ckCc',
                    'plan_code'     => 'PLN_yrs9eb5u94ac0ek',
                    'description'   => null,
                    'amount'        => 10000,
                    'interval'      => 'monthly',
                    'send_invoices' => true,
                    'send_sms'      => true,
                    'currency'      => 'ZAR',
                ],
                'authorization'       => [
                    'authorization_code' => 'AUTH_96ry2sqcxl',
                    'bin'                => '408408',
                    'last4'              => '4081',
                    'exp_month'          => '12',
                    'exp_year'           => '2030',
                    'channel'            => 'card',
                    'card_type'          => 'visa',
                    'bank'               => 'TEST BANK',
                    'country_code'       => 'ZA',
                    'brand'              => 'visa',
                    'reusable'           => true,
                    'signature'          => 'SIG_XtQmL8nBieEY6wPi0zzP',
                    'account_name'       => null,
                ],
                'customer'            => [
                    'id'                         => 61536197,
                    'first_name'                 => 'John',
                    'last_name'                  => 'doe',
                    'email'                      => 'howdy@hi5ve.digital',
                    'customer_code'              => 'CUS_vsdmoj9tete6il1',
                    'phone'                      => null,
                    'metadata'                   => null,
                    'risk_action'                => 'default',
                    'international_format_phone' => null,
                ],
                'invoices'            => [],
                'invoices_history'    => [],
                'invoice_limit'       => 0,
                'split_code'          => null,
                'most_recent_invoice' => null,
                'created_at'          => '2021-11-15T11:00:09.000Z',
            ],
        ];

        // Create signature
        $hash = hash_hmac('sha512', json_encode($payload), config('subscription.credentials.paystack.secret'));

        // Send webhook
        $this
            ->withHeader('x-paystack-signature', $hash)
            ->postJson('/api/subscriptions/paystack/webhooks', $payload)
            ->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'status'  => 'cancelled',
            'ends_at' => $cancelledAt,
        ]);

        Event::assertDispatched(fn (SubscriptionWasCancelled $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function paystack_webhook_charge_success()
    {
        $user = User::factory()
            ->create([
                'email' => 'howdy@hi5ve.digital',
            ]);

        $plan = Plan::factory()
            ->hasDrivers([
                'driver'         => 'paystack',
                'driver_plan_id' => 'PLN_n6yhgbksc3lxwd8',
            ])
            ->create();

        Subscription::factory()
            ->hasDriver([
                'driver_subscription_id' => 'SUB_uh7ikqratwbdql5',
                'driver'                 => 'paystack',
            ])
            ->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status'  => 'active',
            ]);

        Http::fake([
            'https://api.paystack.co/subscription/*' => Http::response([
                'status'  => true,
                'message' => 'Subscription retrieved successfully',
                'data'    => [
                    'invoices'          => [
                    ],
                    'customer'          => [
                        'first_name'    => 'BoJack',
                        'last_name'     => 'Horseman',
                        'email'         => 'bojack@horsinaround.com',
                        'phone'         => null,
                        'metadata'      => [
                            'photos' => [
                                [
                                    'type'      => 'twitter',
                                    'typeId'    => 'twitter',
                                    'typeName'  => 'Twitter',
                                    'url'       => 'https://d2ojpxxtu63wzl.cloudfront.net/static/61b1a0a1d4dda2c9fe9e165fed07f812_a722ae7148870cc2e33465d1807dfdc6efca33ad2c4e1f8943a79eead3c21311',
                                    'isPrimary' => false,
                                ],
                            ],
                        ],
                        'domain'        => 'test',
                        'customer_code' => 'CUS_xnxdt6s1zg1f4nx',
                        'id'            => 1173,
                        'integration'   => 100032,
                        'createdAt'     => '2016-03-29T20:03:09.000Z',
                        'updatedAt'     => '2016-03-29T20:53:05.000Z',
                    ],
                    'plan'              => [
                        'domain'              => 'test',
                        'name'                => 'Monthly retainer (renamed)',
                        'plan_code'           => 'PLN_n6yhgbksc3lxwd8',
                        'description'         => null,
                        'amount'              => 50000,
                        'interval'            => 'monthly',
                        'send_invoices'       => true,
                        'send_sms'            => true,
                        'hosted_page'         => false,
                        'hosted_page_url'     => null,
                        'hosted_page_summary' => null,
                        'currency'            => 'NGN',
                        'id'                  => 28,
                        'integration'         => 100032,
                        'createdAt'           => '2016-03-29T22:42:50.000Z',
                        'updatedAt'           => '2016-03-29T23:51:41.000Z',
                    ],
                    'integration'       => 100032,
                    'authorization'     => [
                        'authorization_code' => 'AUTH_6tmt288t0o',
                        'bin'                => '408408',
                        'last4'              => '4081',
                        'exp_month'          => '12',
                        'exp_year'           => '2020',
                        'channel'            => 'card',
                        'card_type'          => 'visa visa',
                        'bank'               => 'TEST BANK',
                        'country_code'       => 'NG',
                        'brand'              => 'visa',
                        'reusable'           => true,
                        'signature'          => 'SIG_uSYN4fv1adlAuoij8QXh',
                        'account_name'       => 'BoJack Horseman',
                    ],
                    'domain'            => 'test',
                    'start'             => 1459296064,
                    'status'            => 'active',
                    'quantity'          => 1,
                    'amount'            => 50000,
                    'subscription_code' => 'SUB_vsyqdmlzble3uii',
                    'email_token'       => 'd7gofp6yppn3qz7',
                    'easy_cron_id'      => null,
                    'cron_expression'   => '0 0 28 * *',
                    'next_payment_date' => '2016-04-28T07:00:00.000Z',
                    'open_invoice'      => null,
                    'id'                => 9,
                    'createdAt'         => '2016-03-30T00:01:04.000Z',
                    'updatedAt'         => '2016-03-30T00:22:58.000Z',
                ],
            ]),
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
                'plan'                 => [
                    'id'            => 192862,
                    'name'          => 'Professional Pack',
                    'plan_code'     => 'PLN_n6yhgbksc3lxwd8',
                    'description'   => null,
                    'amount'        => 1000,
                    'interval'      => 'monthly',
                    'send_invoices' => true,
                    'send_sms'      => true,
                    'currency'      => 'ZAR',
                ],
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
            ->assertDatabaseHas('subscriptions', [
                'renews_at' => '2016-04-28 07:00:00',
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'note'      => 'Professional Pack',
                'currency'  => 'ZAR',
                'amount'    => 10,
                'driver'    => 'paystack',
                'reference' => 'gD2au4Jyuc',
            ]);
    }

    /**
     * @test
     */
    public function paystack_webhook_charge_success_and_credit_user_balance()
    {
        $user = User::factory()
            ->create([
                'email' => 'howdy@hi5ve.digital',
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
}
