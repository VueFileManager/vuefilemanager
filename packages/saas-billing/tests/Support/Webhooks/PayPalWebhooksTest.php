<?php
namespace Tests\Support\Webhooks;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\Mocking\PayPal\VerifyWebhookPayPalMocksClass;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCancelled;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Subscriptions\Notifications\SubscriptionWasCreatedNotification;

class PayPalWebhooksTest extends TestCase
{
    /**
     * @test
     */
    public function paypal_webhook_create_subscription()
    {
        $user = User::factory()
            ->create();

        // Create plan with features
        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'paypal',
            ])
            ->hasFixedFeatures(2)
            ->create();

        resolve(VerifyWebhookPayPalMocksClass::class)();

        // Send webhook
        $this
            ->postJson('/api/subscriptions/paypal/webhooks', [
                'id'               => 'WH-8A715371GG332831A-4MM87741Y6956121U',
                'event_version'    => '1.0',
                'create_time'      => '2021-11-10T06:53:31.290Z',
                'resource_type'    => 'subscription',
                'resource_version' => '2.0',
                'event_type'       => 'BILLING.SUBSCRIPTION.CREATED',
                'summary'          => 'Subscription created',
                'resource'         => [
                    'start_time'      => '2021-11-10T06:53:31Z',
                    'quantity'        => '1',
                    'create_time'     => '2021-11-10T06:53:31Z',
                    'custom_id'       => $user->id,
                    'links'           => [
                        [
                            'href'   => 'https://www.sandbox.paypal.com/webapps/billing/subscriptions?ba_token=BA-88260049KY7916255',
                            'rel'    => 'approve',
                            'method' => 'GET',
                        ],
                        [
                            'href'   => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-KHY6B042F1YA',
                            'rel'    => 'edit',
                            'method' => 'PATCH',
                        ],
                        [
                            'href'   => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-KHY6B042F1YA',
                            'rel'    => 'self',
                            'method' => 'GET',
                        ],
                    ],
                    'id'              => 'I-KHY6B042F1YA',
                    'plan_overridden' => false,
                    'plan_id'         => $plan->driverId('paypal'),
                    'status'          => 'APPROVAL_PENDING',
                ],
            ])
            ->assertOk();

        // Check if subscription was created
        $subscription = Subscription::first();

        // Check relationships are correct
        $this->assertEquals($user->id, $subscription->user->id);
        $this->assertEquals($plan->id, $subscription->plan->id);

        $this
            ->assertDatabaseHas('subscriptions', [
                'status' => 'inactive',
            ])
            ->assertDatabaseHas('subscription_drivers', [
                'driver_subscription_id' => 'I-KHY6B042F1YA',
            ]);
    }

    /**
     * @test
     */
    public function paypal_webhook_activated_subscription()
    {
        Event::fake([
            SubscriptionWasCreated::class,
        ]);

        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'paypal',
            ])
            ->create();

        Subscription::factory()
            ->hasDriver([
                'driver_subscription_id' => 'I-FHRP6U0C2SP4',
                'driver'                 => 'paypal',
            ])
            ->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'name'    => $plan->name,
                'status'  => 'inactive',
            ]);

        $this->postJson('/api/subscriptions/paypal/webhooks', [
            'id'               => 'WH-9X2911970S406133P-4W189393B5471690K',
            'event_version'    => '1.0',
            'create_time'      => '2022-04-19T14:59:38.287Z',
            'resource_type'    => 'subscription',
            'resource_version' => '2.0',
            'event_type'       => 'BILLING.SUBSCRIPTION.ACTIVATED',
            'summary'          => 'Subscription activated',
            'resource'         =>
                [
                    'quantity'           => '1',
                    'subscriber'         =>
                        [
                            'email_address'    => 'ernest@azet.sk',
                            'payer_id'         => 'XEBW65LBRMPMA',
                            'name'             =>
                                [
                                    'given_name' => 'Michal',
                                    'surname'    => 'Kamenicky',
                                ],
                            'shipping_address' =>
                                [
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
                    'create_time'        => '2022-04-19T14:59:17Z',
                    'custom_id'          => '0907133b-108e-4187-a6c5-2d0061582628',
                    'plan_overridden'    => false,
                    'shipping_amount'    =>
                        [
                            'currency_code' => 'USD',
                            'value'         => '0.0',
                        ],
                    'start_time'         => '2022-04-19T14:59:00Z',
                    'update_time'        => '2022-04-19T14:59:19Z',
                    'billing_info'       =>
                        [
                            'outstanding_balance'   =>
                                [
                                    'currency_code' => 'USD',
                                    'value'         => '0.0',
                                ],
                            'cycle_executions'      =>
                                [
                                    0 =>
                                        [
                                            'tenure_type'                    => 'REGULAR',
                                            'sequence'                       => 1,
                                            'cycles_completed'               => 1,
                                            'cycles_remaining'               => 0,
                                            'current_pricing_scheme_version' => 1,
                                            'total_cycles'                   => 0,
                                        ],
                                ],
                            'last_payment'          =>
                                [
                                    'amount' =>
                                        [
                                            'currency_code' => 'USD',
                                            'value'         => '9.99',
                                        ],
                                    'time'   => '2022-04-19T14:59:18Z',
                                ],
                            'next_billing_time'     => '2022-05-19T10:00:00Z',
                            'failed_payments_count' => 0,
                        ],
                    'links'              =>
                        [
                            0 =>
                                [
                                    'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-FHRP6U0C2SP4/cancel',
                                    'rel'     => 'cancel',
                                    'method'  => 'POST',
                                    'encType' => 'application/json',
                                ],
                            1 =>
                                [
                                    'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-FHRP6U0C2SP4',
                                    'rel'     => 'edit',
                                    'method'  => 'PATCH',
                                    'encType' => 'application/json',
                                ],
                            2 =>
                                [
                                    'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-FHRP6U0C2SP4',
                                    'rel'     => 'self',
                                    'method'  => 'GET',
                                    'encType' => 'application/json',
                                ],
                            3 =>
                                [
                                    'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-FHRP6U0C2SP4/suspend',
                                    'rel'     => 'suspend',
                                    'method'  => 'POST',
                                    'encType' => 'application/json',
                                ],
                            4 =>
                                [
                                    'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-FHRP6U0C2SP4/capture',
                                    'rel'     => 'capture',
                                    'method'  => 'POST',
                                    'encType' => 'application/json',
                                ],
                        ],
                    'id'                 => 'I-FHRP6U0C2SP4',
                    'plan_id'            => 'P-9F013305BN313892PMI5BTZA',
                    'status'             => 'ACTIVE',
                    'status_update_time' => '2022-04-19T14:59:19Z',
                ],
            'links'            =>
                [
                    0 =>
                        [
                            'href'   => 'https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9X2911970S406133P-4W189393B5471690K',
                            'rel'    => 'self',
                            'method' => 'GET',
                        ],
                    1 =>
                        [
                            'href'   => 'https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9X2911970S406133P-4W189393B5471690K/resend',
                            'rel'    => 'resend',
                            'method' => 'POST',
                        ],
                ],
        ])->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'status' => 'active',
        ]);

        // Get subscription
        $subscription = Subscription::first();

        Notification::assertSentTo($user, SubscriptionWasCreatedNotification::class);

        Event::assertDispatched(fn (SubscriptionWasCreated $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function paypal_webhook_update_subscription()
    {
        Event::fake([
            SubscriptionWasUpdated::class,
        ]);

        [$plan, $planHigher] = Plan::factory()
            ->hasDrivers([
                'driver' => 'paypal',
            ])
            ->count(2)
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paypal',
            ])
            ->create([
                'plan_id' => $plan->id,
                'name'    => $plan->name,
                'status'  => 'active',
            ]);

        resolve(VerifyWebhookPayPalMocksClass::class)();

        // Send webhook
        $this->postJson('/api/subscriptions/paypal/webhooks', [
            'id'            => 'WH-5W900153L71312432-5CW911939G6805917',
            'create_time'   => '2021-11-16T10:50:47.259Z',
            'resource_type' => 'subscription',
            'event_type'    => 'BILLING.SUBSCRIPTION.UPDATED',
            'summary'       => 'Subscription updated',
            'resource'      => [
                'quantity'           => '1',
                'subscriber'         => [
                    'email_address'    => 'ernest@azet.sk',
                    'payer_id'         => 'XEBW65LBRMPMA',
                    'name'             => [
                        'given_name' => 'Michal',
                        'surname'    => 'Kamenicky',
                    ],
                    'shipping_address' => [
                        'address' => [
                            'address_line_1' => '1 Main St',
                            'admin_area_2'   => 'San Jose',
                            'admin_area_1'   => 'CA',
                            'postal_code'    => '95131',
                            'country_code'   => 'US',
                        ],
                    ],
                ],
                'create_time'        => '2021-11-10T06:42:47Z',
                'custom_id'          => 'user_id_howdy',
                'plan_overridden'    => false,
                'shipping_amount'    => [
                    'currency_code' => 'USD',
                    'value'         => '0.0',
                ],
                'start_time'         => '2021-11-10T06:42:22Z',
                'update_time'        => '2021-11-10T06:42:48Z',
                'billing_info'       => [
                    'outstanding_balance'   => [
                        'currency_code' => 'USD',
                        'value'         => '0.0',
                    ],
                    'cycle_executions'      => [
                        [
                            'tenure_type'                    => 'REGULAR',
                            'sequence'                       => 1,
                            'cycles_completed'               => 0,
                            'cycles_remaining'               => 0,
                            'current_pricing_scheme_version' => 1,
                            'total_cycles'                   => 0,
                        ],
                    ],
                    'last_payment'          => [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value'         => '500.0',
                        ],
                        'time'   => '2021-11-10T06:42:48Z',
                    ],
                    'next_billing_time'     => '2021-12-10T10:00:00Z',
                    'failed_payments_count' => 0,
                ],
                'links'              => [
                    [
                        'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-GW6LUW7CW1AC/cancel',
                        'rel'     => 'cancel',
                        'method'  => 'POST',
                        'encType' => 'application/json',
                    ],
                    [
                        'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-GW6LUW7CW1AC',
                        'rel'     => 'edit',
                        'method'  => 'PATCH',
                        'encType' => 'application/json',
                    ],
                    [
                        'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-GW6LUW7CW1AC',
                        'rel'     => 'self',
                        'method'  => 'GET',
                        'encType' => 'application/json',
                    ],
                    [
                        'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-GW6LUW7CW1AC/suspend',
                        'rel'     => 'suspend',
                        'method'  => 'POST',
                        'encType' => 'application/json',
                    ],
                    [
                        'href'    => 'https://api.sandbox.paypal.com/v1/billing/subscriptions/I-GW6LUW7CW1AC/capture',
                        'rel'     => 'capture',
                        'method'  => 'POST',
                        'encType' => 'application/json',
                    ],
                ],
                'id'                 => $subscription->driverId(),
                'plan_id'            => $planHigher->driverId('paypal'),
                'status'             => 'ACTIVE',
                'status_update_time' => '2021-11-10T06:42:48Z',
            ],
        ])
            ->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'plan_id' => $planHigher->id,
            'name'    => $planHigher->name,
        ]);

        Event::assertDispatched(fn (SubscriptionWasUpdated $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function paypal_webhook_cancel_subscription()
    {
        Event::fake([
            SubscriptionWasCancelled::class,
        ]);

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paypal',
            ])
            ->create([
                'status'     => 'active',
                'ends_at'    => null,
                'created_at' => now()->subDays(14),
            ]);

        $cancelledAt = now()->addDays(14);

        resolve(VerifyWebhookPayPalMocksClass::class)();

        // Send webhook
        $this->postJson('/api/subscriptions/paypal/webhooks', [
            'id'            => 'WH-UY687577TY25889J9-2R6T55435R66168Y6',
            'create_time'   => '2018-19-12T22:20:32.000Z',
            'resource_type' => 'subscription',
            'event_type'    => 'BILLING.SUBSCRIPTION.CANCELLED',
            'summary'       => 'A billing subscription was cancelled.',
            'resource'      => [
                'quantity'           => '20',
                'subscriber'         => [
                    'name'             => [
                        'given_name' => 'John',
                        'surname'    => 'Doe',
                    ],
                    'email_address'    => 'customer@example.com',
                    'shipping_address' => [
                        'name'    => [
                            'full_name' => 'John Doe',
                        ],
                        'address' => [
                            'address_line_1' => '2211 N First Street',
                            'address_line_2' => 'Building 17',
                            'admin_area_2'   => 'San Jose',
                            'admin_area_1'   => 'CA',
                            'postal_code'    => '95131',
                            'country_code'   => 'US',
                        ],
                    ],
                ],
                'create_time'        => '2018-12-10T21:20:49Z',
                'shipping_amount'    => [
                    'currency_code' => 'USD',
                    'value'         => '10.00',
                ],
                'start_time'         => '2018-11-01T00:00:00Z',
                'update_time'        => '2018-12-10T21:20:49Z',
                'billing_info'       => [
                    'outstanding_balance'   => [
                        'currency_code' => 'USD',
                        'value'         => '10.00',
                    ],
                    'cycle_executions'      => [
                        [
                            'tenure_type'                    => 'TRIAL',
                            'sequence'                       => 1,
                            'cycles_completed'               => 1,
                            'cycles_remaining'               => 0,
                            'current_pricing_scheme_version' => 1,
                        ],
                        [
                            'tenure_type'                    => 'REGULAR',
                            'sequence'                       => 2,
                            'cycles_completed'               => 1,
                            'cycles_remaining'               => 0,
                            'current_pricing_scheme_version' => 1,
                        ],
                    ],
                    'last_payment'          => [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value'         => '500.00',
                        ],
                        'time'   => '2018-12-01T01:20:49Z',
                    ],
                    'next_billing_time'     => $cancelledAt,
                    'final_payment_time'    => '2020-01-01T00:20:49Z',
                    'failed_payments_count' => 2,
                ],
                'links'              => [
                    [
                        'href'   => 'https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G',
                        'rel'    => 'self',
                        'method' => 'GET',
                    ],
                    [
                        'href'   => 'https://api.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G',
                        'rel'    => 'edit',
                        'method' => 'PATCH',
                    ],
                ],
                'id'                 => $subscription->driverId(),
                'plan_id'            => 'P-5ML4271244454362WXNWU5NQ',
                'auto_renewal'       => true,
                'status'             => 'CANCELLED',
                'status_update_time' => '2018-12-10T21:20:49Z',
            ],
        ])
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
    public function paypal_webhook_payment_sale_completed()
    {
        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'paypal',
            ])
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paypal',
            ])
            ->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'name'    => $plan->name,
                'status'  => 'inactive',
            ]);

        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token'            => Http::response([
                'scope'        => 'scope',
                'access_token' => 'jnjleqngtlq3l34jn6l2346n2l4',
                'token_type'   => 'Bearer',
                'app_id'       => 'APP-80W284485P519543T',
                'expires_in'   => 31349,
                'nonce'        => 'nonce',
            ], 204),
            'https://api-m.sandbox.paypal.com/v1/billing/subscriptions/*' => Http::response([
                'id'                 => $subscription->driverId('paypal'),
                'plan_id'            => $plan->driverId('paypal'),
                'start_time'         => '2019-04-10T07:00:00Z',
                'quantity'           => '20',
                'shipping_amount'    => [
                    'currency_code' => 'USD',
                    'value'         => '10.0',
                ],
                'subscriber'         => [
                    'shipping_address' => [
                        'name'    => [
                            'full_name' => 'John Doe',
                        ],
                        'address' => [
                            'address_line_1' => '2211 N First Street',
                            'address_line_2' => 'Building 17',
                            'admin_area_2'   => 'San Jose',
                            'admin_area_1'   => 'CA',
                            'postal_code'    => '95131',
                            'country_code'   => 'US',
                        ],
                    ],
                    'name'             => [
                        'given_name' => 'John',
                        'surname'    => 'Doe',
                    ],
                    'email_address'    => 'customer@example.com',
                    'payer_id'         => '2J6QB8YJQSJRJ',
                ],
                'billing_info'       => [
                    'outstanding_balance'   => [
                        'currency_code' => 'USD',
                        'value'         => '1.0',
                    ],
                    'cycle_executions'      => [
                        [
                            'tenure_type'      => 'TRIAL',
                            'sequence'         => 1,
                            'cycles_completed' => 0,
                            'cycles_remaining' => 2,
                            'total_cycles'     => 2,
                        ],
                        [
                            'tenure_type'      => 'TRIAL',
                            'sequence'         => 2,
                            'cycles_completed' => 0,
                            'cycles_remaining' => 3,
                            'total_cycles'     => 3,
                        ],
                        [
                            'tenure_type'      => 'REGULAR',
                            'sequence'         => 3,
                            'cycles_completed' => 0,
                            'cycles_remaining' => 12,
                            'total_cycles'     => 12,
                        ],
                    ],
                    'last_payment'          => [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value'         => '1.15',
                        ],
                        'time'   => '2019-04-09T10:27:20Z',
                    ],
                    'next_billing_time'     => '2019-04-09T10:26:04Z',
                    'failed_payments_count' => 0,
                ],
                'create_time'        => '2019-04-09T10:26:04Z',
                'update_time'        => '2019-04-09T10:27:27Z',
                'links'              => [
                    [
                        'href'   => 'https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/cancel',
                        'rel'    => 'cancel',
                        'method' => 'POST',
                    ],
                    [
                        'href'   => 'https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G',
                        'rel'    => 'edit',
                        'method' => 'PATCH',
                    ],
                    [
                        'href'   => 'https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G',
                        'rel'    => 'self',
                        'method' => 'GET',
                    ],
                    [
                        'href'   => 'https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/suspend',
                        'rel'    => 'suspend',
                        'method' => 'POST',
                    ],
                    [
                        'href'   => 'https://api-m.paypal.com/v1/billing/subscriptions/I-BW452GLLEP1G/capture',
                        'rel'    => 'capture',
                        'method' => 'POST',
                    ],
                ],
                'status'             => 'ACTIVE',
                'status_update_time' => '2019-04-09T10:27:27Z',
            ]),
        ]);

        resolve(VerifyWebhookPayPalMocksClass::class)();

        $this->postJson('/api/subscriptions/paypal/webhooks', [
            'id'            => 'WH-1T716926534839215-2GJ19575AK355574E',
            'event_version' => '1.0',
            'create_time'   => '2022-03-02T16:43:32.666Z',
            'resource_type' => 'sale',
            'event_type'    => 'PAYMENT.SALE.COMPLETED',
            'summary'       => 'Payment completed for $ 29.99 USD',
            'resource'      => [
                'amount'                      =>
                    [
                        'total'    => '29.99',
                        'currency' => 'USD',
                        'details'  =>
                            [
                                'subtotal' => '29.99',
                            ],
                    ],
                'payment_mode'                => 'INSTANT_TRANSFER',
                'create_time'                 => '2022-03-02T16:43:15Z',
                'custom'                      => $user->id,
                'transaction_fee'             =>
                    [
                        'currency' => 'USD',
                        'value'    => '1.47',
                    ],
                'billing_agreement_id'        => $subscription->driverId('paypal'),
                'update_time'                 => '2022-03-02T16:43:15Z',
                'protection_eligibility_type' => 'ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE',
                'protection_eligibility'      => 'ELIGIBLE',
                'links'                       =>
                    [
                        0 =>
                            [
                                'method' => 'GET',
                                'rel'    => 'self',
                                'href'   => 'https://api.sandbox.paypal.com/v1/payments/sale/4XF54837E41172519',
                            ],
                        1 =>
                            [
                                'method' => 'POST',
                                'rel'    => 'refund',
                                'href'   => 'https://api.sandbox.paypal.com/v1/payments/sale/4XF54837E41172519/refund',
                            ],
                    ],
                'id'                          => '4XF54837E41172519',
                'state'                       => 'completed',
                'invoice_number'              => null,
            ],
            'links'         => [
                0 =>
                    [
                        'href'   => 'https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-1T716926534839215-2GJ19575AK355574E',
                        'rel'    => 'self',
                        'method' => 'GET',
                    ],
                1 =>
                    [
                        'href'   => 'https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-1T716926534839215-2GJ19575AK355574E/resend',
                        'rel'    => 'resend',
                        'method' => 'POST',
                    ],
            ],
        ])
            ->assertOk();

        $this->assertDatabaseHas('transactions', [
            'user_id'   => $user->id,
            'type'      => 'charge',
            'status'    => 'completed',
            'note'      => $plan->name,
            'currency'  => 'USD',
            'amount'    => 29.99,
            'driver'    => 'paypal',
            'reference' => $subscription->driverId('paypal'),
        ]);
    }

    /**
     * @test
     */
    public function paypal_webhook_payment_capture_completed()
    {
        $user = User::factory()
            ->create();

        resolve(VerifyWebhookPayPalMocksClass::class)();

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
                                'value'         => '12.49',
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
                                        'full_name' => 'Michal Kamenicky',
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
                                'given_name' => 'Michal',
                                'surname'    => 'Kamenicky',
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
            'links'            =>
                [
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
            ->assertDatabaseHas('balances', [
                'user_id'  => $user->id,
                'amount'   => 12.49,
                'currency' => 'USD',
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'currency'  => 'USD',
                'amount'    => 12.49,
                'driver'    => 'paypal',
                'reference' => '32649052UT384661G',
            ]);
    }
}
