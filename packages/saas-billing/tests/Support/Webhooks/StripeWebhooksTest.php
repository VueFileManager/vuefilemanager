<?php
namespace Tests\Support\Webhooks;

use Carbon\Carbon;
use Tests\TestCase;
use Tests\Models\User;
use Tests\Helpers\StripeTestHelpers;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\Mocking\Stripe\GetSubscriptionStripeMocksClass;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCreated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasExpired;
use VueFileManager\Subscription\Support\Events\SubscriptionWasUpdated;
use VueFileManager\Subscription\Support\Events\SubscriptionWasCancelled;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\Subscriptions\Notifications\SubscriptionWasCreatedNotification;
use VueFileManager\Subscription\Support\Miscellaneous\Stripe\Notifications\ConfirmStripePaymentNotification;

class StripeWebhooksTest extends TestCase
{
    use StripeTestHelpers;

    /**
     * @test
     */
    public function stripe_webhook_create_fixed_subscription()
    {
        Event::fake([
            SubscriptionWasUpdated::class,
        ]);

        $user = User::factory()
            ->create();

        Customer::create([
            'user_id'        => $user->id,
            'driver_user_id' => 'cus_KhYcsbpXlseQU0',
            'driver'         => 'stripe',
        ]);

        // Create plan with features
        $plan = Plan::factory()
            ->hasDrivers([
                'driver'         => 'stripe',
                'driver_plan_id' => 'price_1K1vL5B9m4sTKy1qrkbJ7QEF',
            ])
            ->hasFixedFeatures(2)
            ->create();

        $payload = [
            'id'               => 'evt_1K2AJ6B9m4sTKy1qS04uRoOf',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638432379,
            'data'             =>
                [
                    'object' =>
                        [
                            'id'                                => 'sub_1K2AJ4B9m4sTKy1qtr8XNc2D',
                            'object'                            => 'subscription',
                            'application_fee_percent'           => null,
                            'automatic_tax'                     =>
                                [
                                    'enabled' => false,
                                ],
                            'billing_cycle_anchor'              => 1638432378,
                            'billing_thresholds'                => null,
                            'cancel_at'                         => null,
                            'cancel_at_period_end'              => false,
                            'canceled_at'                       => null,
                            'collection_method'                 => 'charge_automatically',
                            'created'                           => 1638432378,
                            'current_period_end'                => 1669968378,
                            'current_period_start'              => 1638432378,
                            'customer'                          => 'cus_KhYcsbpXlseQU0',
                            'days_until_due'                    => null,
                            'default_payment_method'            => null,
                            'default_source'                    => null,
                            'default_tax_rates'                 =>
                                [
                                ],
                            'discount'                          => null,
                            'ended_at'                          => null,
                            'items'                             =>
                                [
                                    'object'      => 'list',
                                    'data'        => [
                                        [
                                            'id'                 => 'si_KhZR5Hq52f5FJw',
                                            'object'             => 'subscription_item',
                                            'billing_thresholds' => null,
                                            'created'            => 1638432379,
                                            'metadata'           =>
                                                [
                                                ],
                                            'plan'               =>
                                                [
                                                    'id'                => 'price_1K1vL5B9m4sTKy1qrkbJ7QEF',
                                                    'object'            => 'plan',
                                                    'active'            => true,
                                                    'aggregate_usage'   => null,
                                                    'amount'            => 9949,
                                                    'amount_decimal'    => '9949',
                                                    'billing_scheme'    => 'per_unit',
                                                    'created'           => 1638374843,
                                                    'currency'          => 'usd',
                                                    'interval'          => 'year',
                                                    'interval_count'    => 1,
                                                    'livemode'          => false,
                                                    'metadata'          =>
                                                        [
                                                        ],
                                                    'nickname'          => null,
                                                    'product'           => 'prod_KhJzsgmz5m457o',
                                                    'tiers_mode'        => null,
                                                    'transform_usage'   => null,
                                                    'trial_period_days' => null,
                                                    'usage_type'        => 'licensed',
                                                ],
                                            'price'              =>
                                                [
                                                    'id'                  => 'price_1K1vL5B9m4sTKy1qrkbJ7QEF',
                                                    'object'              => 'price',
                                                    'active'              => true,
                                                    'billing_scheme'      => 'per_unit',
                                                    'created'             => 1638374843,
                                                    'currency'            => 'usd',
                                                    'livemode'            => false,
                                                    'lookup_key'          => null,
                                                    'metadata'            =>
                                                        [
                                                        ],
                                                    'nickname'            => null,
                                                    'product'             => 'prod_KhJzsgmz5m457o',
                                                    'recurring'           =>
                                                        [
                                                            'aggregate_usage'   => null,
                                                            'interval'          => 'year',
                                                            'interval_count'    => 1,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'tax_behavior'        => 'unspecified',
                                                    'tiers_mode'          => null,
                                                    'transform_quantity'  => null,
                                                    'type'                => 'recurring',
                                                    'unit_amount'         => 9949,
                                                    'unit_amount_decimal' => '9949',
                                                ],
                                            'quantity'           => 1,
                                            'subscription'       => 'sub_1K2AJ4B9m4sTKy1qtr8XNc2D',
                                            'tax_rates'          =>
                                                [
                                                ],
                                        ],
                                    ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/subscription_items?subscription=sub_1K2AJ4B9m4sTKy1qtr8XNc2D',
                                ],
                            'latest_invoice'                    => 'in_1K2AJ4B9m4sTKy1qMLOPkwSY',
                            'livemode'                          => false,
                            'metadata'                          =>
                                [
                                ],
                            'next_pending_invoice_item_invoice' => null,
                            'pause_collection'                  => null,
                            'payment_settings'                  =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'pending_invoice_item_interval'     => null,
                            'pending_setup_intent'              => 'seti_1K2AJ5B9m4sTKy1q6Eli1sqb',
                            'pending_update'                    => null,
                            'plan'                              =>
                                [
                                    'id'                => 'price_1K1vL5B9m4sTKy1qrkbJ7QEF',
                                    'object'            => 'plan',
                                    'active'            => true,
                                    'aggregate_usage'   => null,
                                    'amount'            => 9949,
                                    'amount_decimal'    => '9949',
                                    'billing_scheme'    => 'per_unit',
                                    'created'           => 1638374843,
                                    'currency'          => 'usd',
                                    'interval'          => 'year',
                                    'interval_count'    => 1,
                                    'livemode'          => false,
                                    'metadata'          =>
                                        [
                                        ],
                                    'nickname'          => null,
                                    'product'           => 'prod_KhJzsgmz5m457o',
                                    'tiers_mode'        => null,
                                    'transform_usage'   => null,
                                    'trial_period_days' => null,
                                    'usage_type'        => 'licensed',
                                ],
                            'quantity'                          => 1,
                            'schedule'                          => null,
                            'start_date'                        => 1638432378,
                            'status'                            => 'active',
                            'transfer_data'                     => null,
                            'trial_end'                         => null,
                            'trial_start'                       => null,
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_E392CXtmClwEn8',
                    'idempotency_key' => 'a26d8d85-67a3-47f8-a3a6-be8a945bf25a',
                ],
            'type'             => 'customer.subscription.created',
        ];

        // Send webhook
        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        // Check if subscription was created
        $subscription = Subscription::first();

        // Check relationships are correct
        $this->assertEquals($user->id, $subscription->user->id);
        $this->assertEquals($plan->id, $subscription->plan->id);

        $this->assertDatabaseHas('subscriptions', [
            'status' => 'inactive',
        ]);

        $this->assertDatabaseHas('subscription_drivers', [
            'driver_subscription_id' => 'sub_1K2AJ4B9m4sTKy1qtr8XNc2D',
            'driver'                 => 'stripe',
        ]);

        Event::assertNothingDispatched();
    }

    /**
     * @test
     */
    public function stripe_webhook_activate_subscription()
    {
        Event::fake([
            SubscriptionWasCreated::class,
        ]);

        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'stripe',
            ])
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver'                 => 'stripe',
                'driver_subscription_id' => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
            ])
            ->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status'  => 'inactive',
                'ends_at' => null,
            ]);

        $payload = [
            'id'               => 'evt_1K2BE4B9m4sTKy1qd8N60DtV',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638435911,
            'data'             =>
                [
                    'object'              =>
                        [
                            'id'                                => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                            'object'                            => 'subscription',
                            'application_fee_percent'           => null,
                            'automatic_tax'                     =>
                                [
                                    'enabled' => false,
                                ],
                            'billing_cycle_anchor'              => 1638435908,
                            'billing_thresholds'                => null,
                            'cancel_at'                         => null,
                            'cancel_at_period_end'              => false,
                            'canceled_at'                       => null,
                            'collection_method'                 => 'charge_automatically',
                            'created'                           => 1638435908,
                            'current_period_end'                => 1638435908,
                            'current_period_start'              => 1638435908,
                            'customer'                          => 'cus_KhaOUge4a5x8hm',
                            'days_until_due'                    => null,
                            'default_payment_method'            => null,
                            'default_source'                    => null,
                            'default_tax_rates'                 =>
                                [
                                ],
                            'discount'                          => null,
                            'ended_at'                          => null,
                            'items'                             =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'                 => 'si_KhaOoyIprhNoTc',
                                                    'object'             => 'subscription_item',
                                                    'billing_thresholds' => null,
                                                    'created'            => 1638435908,
                                                    'metadata'           =>
                                                        [
                                                        ],
                                                    'plan'               =>
                                                        [
                                                            'id'                => 'plan_KhaOxXzYP2Nova',
                                                            'object'            => 'plan',
                                                            'active'            => true,
                                                            'aggregate_usage'   => null,
                                                            'amount'            => 2000,
                                                            'amount_decimal'    => '2000',
                                                            'billing_scheme'    => 'per_unit',
                                                            'created'           => 1638435907,
                                                            'currency'          => 'usd',
                                                            'interval'          => 'month',
                                                            'interval_count'    => 1,
                                                            'livemode'          => false,
                                                            'metadata'          =>
                                                                [
                                                                ],
                                                            'nickname'          => null,
                                                            'product'           => 'prod_KhaO4s6RUDhOWF',
                                                            'tiers_mode'        => null,
                                                            'transform_usage'   => null,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'price'              =>
                                                        [
                                                            'id'                  => 'plan_KhaOxXzYP2Nova',
                                                            'object'              => 'price',
                                                            'active'              => true,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638435907,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [
                                                                ],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_KhaO4s6RUDhOWF',
                                                            'recurring'           =>
                                                                [
                                                                    'aggregate_usage'   => null,
                                                                    'interval'          => 'month',
                                                                    'interval_count'    => 1,
                                                                    'trial_period_days' => null,
                                                                    'usage_type'        => 'licensed',
                                                                ],
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'recurring',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'quantity'           => 1,
                                                    'subscription'       => 'sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                                    'tax_rates'          =>
                                                        [
                                                        ],
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/subscription_items?subscription=sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                ],
                            'latest_invoice'                    => 'in_1K2BE0B9m4sTKy1qiscPsYFt',
                            'livemode'                          => false,
                            'metadata'                          =>
                                [
                                    'foo' => 'bar',
                                ],
                            'next_pending_invoice_item_invoice' => null,
                            'pause_collection'                  => null,
                            'payment_settings'                  =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'pending_invoice_item_interval'     => null,
                            'pending_setup_intent'              => null,
                            'pending_update'                    => null,
                            'plan'                              =>
                                [
                                    'id'                => $plan->driverId('stripe'),
                                    'object'            => 'plan',
                                    'active'            => true,
                                    'aggregate_usage'   => null,
                                    'amount'            => 2000,
                                    'amount_decimal'    => '2000',
                                    'billing_scheme'    => 'per_unit',
                                    'created'           => 1638435907,
                                    'currency'          => 'usd',
                                    'interval'          => 'month',
                                    'interval_count'    => 1,
                                    'livemode'          => false,
                                    'metadata'          =>
                                        [
                                        ],
                                    'nickname'          => null,
                                    'product'           => 'prod_KhaO4s6RUDhOWF',
                                    'tiers_mode'        => null,
                                    'transform_usage'   => null,
                                    'trial_period_days' => null,
                                    'usage_type'        => 'licensed',
                                ],
                            'quantity'                          => 1,
                            'schedule'                          => null,
                            'start_date'                        => 1638435908,
                            'status'                            => 'active',
                            'transfer_data'                     => null,
                            'trial_end'                         => null,
                            'trial_start'                       => null,
                        ],
                    'previous_attributes' =>
                        [
                            'metadata' =>
                                [
                                    'foo' => null,
                                ],
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_QlWcOYveSlsar5',
                    'idempotency_key' => '34faedb2-9b1b-4bc0-a3a6-701c437efc81',
                ],
            'type'             => 'customer.subscription.updated',
        ];

        // Send webhook
        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'status' => 'active',
        ]);

        Notification::assertSentTo($user, SubscriptionWasCreatedNotification::class);

        Event::assertDispatched(fn (SubscriptionWasCreated $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function stripe_webhook_cancel_subscription()
    {
        Event::fake([
            SubscriptionWasCancelled::class,
        ]);

        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'stripe',
            ])
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver'                 => 'stripe',
                'driver_subscription_id' => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
            ])
            ->create([
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'ends_at'    => null,
                'created_at' => now()->subDays(14),
            ]);

        $cancelledAt = now()->addDays(14);

        $payload = [
            'id'               => 'evt_1K2BE4B9m4sTKy1qd8N60DtV',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638435911,
            'data'             =>
                [
                    'object'              =>
                        [
                            'id'                                => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                            'object'                            => 'subscription',
                            'application_fee_percent'           => null,
                            'automatic_tax'                     =>
                                [
                                    'enabled' => false,
                                ],
                            'billing_cycle_anchor'              => 1638435908,
                            'billing_thresholds'                => null,
                            'cancel_at'                         => null,
                            'cancel_at_period_end'              => true,
                            'canceled_at'                       => null,
                            'collection_method'                 => 'charge_automatically',
                            'created'                           => 1638435908,
                            'current_period_end'                => $cancelledAt->timestamp,
                            'current_period_start'              => 1638435908,
                            'customer'                          => 'cus_KhaOUge4a5x8hm',
                            'days_until_due'                    => null,
                            'default_payment_method'            => null,
                            'default_source'                    => null,
                            'default_tax_rates'                 =>
                                [
                                ],
                            'discount'                          => null,
                            'ended_at'                          => null,
                            'items'                             =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'                 => 'si_KhaOoyIprhNoTc',
                                                    'object'             => 'subscription_item',
                                                    'billing_thresholds' => null,
                                                    'created'            => 1638435908,
                                                    'metadata'           =>
                                                        [
                                                        ],
                                                    'plan'               =>
                                                        [
                                                            'id'                => 'plan_KhaOxXzYP2Nova',
                                                            'object'            => 'plan',
                                                            'active'            => true,
                                                            'aggregate_usage'   => null,
                                                            'amount'            => 2000,
                                                            'amount_decimal'    => '2000',
                                                            'billing_scheme'    => 'per_unit',
                                                            'created'           => 1638435907,
                                                            'currency'          => 'usd',
                                                            'interval'          => 'month',
                                                            'interval_count'    => 1,
                                                            'livemode'          => false,
                                                            'metadata'          =>
                                                                [
                                                                ],
                                                            'nickname'          => null,
                                                            'product'           => 'prod_KhaO4s6RUDhOWF',
                                                            'tiers_mode'        => null,
                                                            'transform_usage'   => null,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'price'              =>
                                                        [
                                                            'id'                  => 'plan_KhaOxXzYP2Nova',
                                                            'object'              => 'price',
                                                            'active'              => true,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638435907,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [
                                                                ],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_KhaO4s6RUDhOWF',
                                                            'recurring'           =>
                                                                [
                                                                    'aggregate_usage'   => null,
                                                                    'interval'          => 'month',
                                                                    'interval_count'    => 1,
                                                                    'trial_period_days' => null,
                                                                    'usage_type'        => 'licensed',
                                                                ],
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'recurring',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'quantity'           => 1,
                                                    'subscription'       => 'sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                                    'tax_rates'          =>
                                                        [
                                                        ],
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/subscription_items?subscription=sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                ],
                            'latest_invoice'                    => 'in_1K2BE0B9m4sTKy1qiscPsYFt',
                            'livemode'                          => false,
                            'metadata'                          =>
                                [
                                    'foo' => 'bar',
                                ],
                            'next_pending_invoice_item_invoice' => null,
                            'pause_collection'                  => null,
                            'payment_settings'                  =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'pending_invoice_item_interval'     => null,
                            'pending_setup_intent'              => null,
                            'pending_update'                    => null,
                            'plan'                              =>
                                [
                                    'id'                => $plan->driverId('stripe'),
                                    'object'            => 'plan',
                                    'active'            => true,
                                    'aggregate_usage'   => null,
                                    'amount'            => 2000,
                                    'amount_decimal'    => '2000',
                                    'billing_scheme'    => 'per_unit',
                                    'created'           => 1638435907,
                                    'currency'          => 'usd',
                                    'interval'          => 'month',
                                    'interval_count'    => 1,
                                    'livemode'          => false,
                                    'metadata'          =>
                                        [
                                        ],
                                    'nickname'          => null,
                                    'product'           => 'prod_KhaO4s6RUDhOWF',
                                    'tiers_mode'        => null,
                                    'transform_usage'   => null,
                                    'trial_period_days' => null,
                                    'usage_type'        => 'licensed',
                                ],
                            'quantity'                          => 1,
                            'schedule'                          => null,
                            'start_date'                        => 1638435908,
                            'status'                            => 'active',
                            'transfer_data'                     => null,
                            'trial_end'                         => null,
                            'trial_start'                       => null,
                        ],
                    'previous_attributes' =>
                        [
                            'metadata' =>
                                [
                                    'foo' => null,
                                ],
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_QlWcOYveSlsar5',
                    'idempotency_key' => '34faedb2-9b1b-4bc0-a3a6-701c437efc81',
                ],
            'type'             => 'customer.subscription.updated',
        ];

        // Send webhook
        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
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
    public function stripe_webhook_incomplete_payment_expired()
    {
        Event::fake([
            SubscriptionWasExpired::class,
        ]);

        $plan = Plan::factory()
            ->hasDrivers([
                'driver' => 'stripe',
            ])
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver'                 => 'stripe',
                'driver_subscription_id' => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
            ])
            ->create([
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'ends_at'    => null,
                'created_at' => now()->subDays(14),
            ]);

        $cancelledAt = now()->addDays(14);

        $payload = [
            'id'               => 'evt_1K2BE4B9m4sTKy1qd8N60DtV',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638435911,
            'data'             =>
                [
                    'object'              =>
                        [
                            'id'                                => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                            'object'                            => 'subscription',
                            'application_fee_percent'           => null,
                            'automatic_tax'                     =>
                                [
                                    'enabled' => false,
                                ],
                            'billing_cycle_anchor'              => 1638435908,
                            'billing_thresholds'                => null,
                            'cancel_at'                         => null,
                            'cancel_at_period_end'              => true,
                            'canceled_at'                       => null,
                            'collection_method'                 => 'charge_automatically',
                            'created'                           => 1638435908,
                            'current_period_end'                => $cancelledAt->timestamp,
                            'current_period_start'              => 1638435908,
                            'customer'                          => 'cus_KhaOUge4a5x8hm',
                            'days_until_due'                    => null,
                            'default_payment_method'            => null,
                            'default_source'                    => null,
                            'default_tax_rates'                 =>
                                [
                                ],
                            'discount'                          => null,
                            'ended_at'                          => null,
                            'items'                             =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'                 => 'si_KhaOoyIprhNoTc',
                                                    'object'             => 'subscription_item',
                                                    'billing_thresholds' => null,
                                                    'created'            => 1638435908,
                                                    'metadata'           =>
                                                        [
                                                        ],
                                                    'plan'               =>
                                                        [
                                                            'id'                => 'plan_KhaOxXzYP2Nova',
                                                            'object'            => 'plan',
                                                            'active'            => true,
                                                            'aggregate_usage'   => null,
                                                            'amount'            => 2000,
                                                            'amount_decimal'    => '2000',
                                                            'billing_scheme'    => 'per_unit',
                                                            'created'           => 1638435907,
                                                            'currency'          => 'usd',
                                                            'interval'          => 'month',
                                                            'interval_count'    => 1,
                                                            'livemode'          => false,
                                                            'metadata'          =>
                                                                [
                                                                ],
                                                            'nickname'          => null,
                                                            'product'           => 'prod_KhaO4s6RUDhOWF',
                                                            'tiers_mode'        => null,
                                                            'transform_usage'   => null,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'price'              =>
                                                        [
                                                            'id'                  => 'plan_KhaOxXzYP2Nova',
                                                            'object'              => 'price',
                                                            'active'              => true,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638435907,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [
                                                                ],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_KhaO4s6RUDhOWF',
                                                            'recurring'           =>
                                                                [
                                                                    'aggregate_usage'   => null,
                                                                    'interval'          => 'month',
                                                                    'interval_count'    => 1,
                                                                    'trial_period_days' => null,
                                                                    'usage_type'        => 'licensed',
                                                                ],
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'recurring',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'quantity'           => 1,
                                                    'subscription'       => 'sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                                    'tax_rates'          =>
                                                        [
                                                        ],
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/subscription_items?subscription=sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                ],
                            'latest_invoice'                    => 'in_1K2BE0B9m4sTKy1qiscPsYFt',
                            'livemode'                          => false,
                            'metadata'                          =>
                                [
                                    'foo' => 'bar',
                                ],
                            'next_pending_invoice_item_invoice' => null,
                            'pause_collection'                  => null,
                            'payment_settings'                  =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'pending_invoice_item_interval'     => null,
                            'pending_setup_intent'              => null,
                            'pending_update'                    => null,
                            'plan'                              =>
                                [
                                    'id'                => $plan->driverId('stripe'),
                                    'object'            => 'plan',
                                    'active'            => true,
                                    'aggregate_usage'   => null,
                                    'amount'            => 2000,
                                    'amount_decimal'    => '2000',
                                    'billing_scheme'    => 'per_unit',
                                    'created'           => 1638435907,
                                    'currency'          => 'usd',
                                    'interval'          => 'month',
                                    'interval_count'    => 1,
                                    'livemode'          => false,
                                    'metadata'          =>
                                        [
                                        ],
                                    'nickname'          => null,
                                    'product'           => 'prod_KhaO4s6RUDhOWF',
                                    'tiers_mode'        => null,
                                    'transform_usage'   => null,
                                    'trial_period_days' => null,
                                    'usage_type'        => 'licensed',
                                ],
                            'quantity'                          => 1,
                            'schedule'                          => null,
                            'start_date'                        => 1638435908,
                            'status'                            => 'incomplete_expired',
                            'transfer_data'                     => null,
                            'trial_end'                         => null,
                            'trial_start'                       => null,
                        ],
                    'previous_attributes' =>
                        [
                            'metadata' =>
                                [
                                    'foo' => null,
                                ],
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_QlWcOYveSlsar5',
                    'idempotency_key' => '34faedb2-9b1b-4bc0-a3a6-701c437efc81',
                ],
            'type'             => 'customer.subscription.updated',
        ];

        // Send webhook
        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'status'  => 'completed',
            'ends_at' => now(),
        ]);

        Event::assertDispatched(fn (SubscriptionWasExpired $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function stripe_webhook_swap_subscription()
    {
        Event::fake([
            SubscriptionWasUpdated::class,
        ]);

        [$plan, $planHigher] = Plan::factory()
            ->hasDrivers(2, [
                'driver' => 'stripe',
            ])
            ->count(2)
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver'                 => 'stripe',
                'driver_subscription_id' => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
            ])
            ->create([
                'plan_id' => $plan->id,
                'name'    => $plan->name,
                'status'  => 'active',
            ]);

        $payload = [
            'id'               => 'evt_1K2BE4B9m4sTKy1qd8N60DtV',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638435911,
            'data'             =>
                [
                    'object'              =>
                        [
                            'id'                                => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                            'object'                            => 'subscription',
                            'application_fee_percent'           => null,
                            'automatic_tax'                     =>
                                [
                                    'enabled' => false,
                                ],
                            'billing_cycle_anchor'              => 1638435908,
                            'billing_thresholds'                => null,
                            'cancel_at'                         => null,
                            'cancel_at_period_end'              => false,
                            'canceled_at'                       => null,
                            'collection_method'                 => 'charge_automatically',
                            'created'                           => 1638435908,
                            'current_period_end'                => 1638435908,
                            'current_period_start'              => 1638435908,
                            'customer'                          => 'cus_KhaOUge4a5x8hm',
                            'days_until_due'                    => null,
                            'default_payment_method'            => null,
                            'default_source'                    => null,
                            'default_tax_rates'                 =>
                                [
                                ],
                            'discount'                          => null,
                            'ended_at'                          => null,
                            'items'                             =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'                 => 'si_KhaOoyIprhNoTc',
                                                    'object'             => 'subscription_item',
                                                    'billing_thresholds' => null,
                                                    'created'            => 1638435908,
                                                    'metadata'           =>
                                                        [
                                                        ],
                                                    'plan'               =>
                                                        [
                                                            'id'                => 'plan_KhaOxXzYP2Nova',
                                                            'object'            => 'plan',
                                                            'active'            => true,
                                                            'aggregate_usage'   => null,
                                                            'amount'            => 2000,
                                                            'amount_decimal'    => '2000',
                                                            'billing_scheme'    => 'per_unit',
                                                            'created'           => 1638435907,
                                                            'currency'          => 'usd',
                                                            'interval'          => 'month',
                                                            'interval_count'    => 1,
                                                            'livemode'          => false,
                                                            'metadata'          =>
                                                                [
                                                                ],
                                                            'nickname'          => null,
                                                            'product'           => 'prod_KhaO4s6RUDhOWF',
                                                            'tiers_mode'        => null,
                                                            'transform_usage'   => null,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'price'              =>
                                                        [
                                                            'id'                  => 'plan_KhaOxXzYP2Nova',
                                                            'object'              => 'price',
                                                            'active'              => true,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638435907,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [
                                                                ],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_KhaO4s6RUDhOWF',
                                                            'recurring'           =>
                                                                [
                                                                    'aggregate_usage'   => null,
                                                                    'interval'          => 'month',
                                                                    'interval_count'    => 1,
                                                                    'trial_period_days' => null,
                                                                    'usage_type'        => 'licensed',
                                                                ],
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'recurring',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'quantity'           => 1,
                                                    'subscription'       => 'sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                                    'tax_rates'          =>
                                                        [
                                                        ],
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/subscription_items?subscription=sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                ],
                            'latest_invoice'                    => 'in_1K2BE0B9m4sTKy1qiscPsYFt',
                            'livemode'                          => false,
                            'metadata'                          =>
                                [
                                    'foo' => 'bar',
                                ],
                            'next_pending_invoice_item_invoice' => null,
                            'pause_collection'                  => null,
                            'payment_settings'                  =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'pending_invoice_item_interval'     => null,
                            'pending_setup_intent'              => null,
                            'pending_update'                    => null,
                            'plan'                              =>
                                [
                                    'id'                => $planHigher->driverId('stripe'),
                                    'object'            => 'plan',
                                    'active'            => true,
                                    'aggregate_usage'   => null,
                                    'amount'            => 2000,
                                    'amount_decimal'    => '2000',
                                    'billing_scheme'    => 'per_unit',
                                    'created'           => 1638435907,
                                    'currency'          => 'usd',
                                    'interval'          => 'month',
                                    'interval_count'    => 1,
                                    'livemode'          => false,
                                    'metadata'          =>
                                        [
                                        ],
                                    'nickname'          => null,
                                    'product'           => 'prod_KhaO4s6RUDhOWF',
                                    'tiers_mode'        => null,
                                    'transform_usage'   => null,
                                    'trial_period_days' => null,
                                    'usage_type'        => 'licensed',
                                ],
                            'quantity'                          => 1,
                            'schedule'                          => null,
                            'start_date'                        => 1638435908,
                            'status'                            => 'active',
                            'transfer_data'                     => null,
                            'trial_end'                         => null,
                            'trial_start'                       => null,
                        ],
                    'previous_attributes' =>
                        [
                            'metadata' =>
                                [
                                    'foo' => null,
                                ],
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_QlWcOYveSlsar5',
                    'idempotency_key' => '34faedb2-9b1b-4bc0-a3a6-701c437efc81',
                ],
            'type'             => 'customer.subscription.updated',
        ];

        // Send webhook
        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
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
    public function stripe_webhook_deleted_subscription()
    {
        Event::fake([
            SubscriptionWasCancelled::class,
        ]);

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver'                 => 'stripe',
                'driver_subscription_id' => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
            ])
            ->create([
                'status'     => 'active',
                'ends_at'    => null,
                'created_at' => now()->subDays(14),
            ]);

        $payload = [
            'id'               => 'evt_1K2AynB9m4sTKy1qEf5UDV6W',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638434965,
            'data'             =>
                [
                    'object' =>
                        [
                            'id'                                => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                            'object'                            => 'subscription',
                            'application_fee_percent'           => null,
                            'automatic_tax'                     =>
                                [
                                    'enabled' => false,
                                ],
                            'billing_cycle_anchor'              => 1638434962,
                            'billing_thresholds'                => null,
                            'cancel_at'                         => null,
                            'cancel_at_period_end'              => false,
                            'canceled_at'                       => 1638434965,
                            'collection_method'                 => 'charge_automatically',
                            'created'                           => 1638434962,
                            'current_period_end'                => 1641113362,
                            'current_period_start'              => 1638434962,
                            'customer'                          => 'cus_Kha9MpKTVVvKbc',
                            'days_until_due'                    => null,
                            'default_payment_method'            => null,
                            'default_source'                    => null,
                            'default_tax_rates'                 =>
                                [
                                ],
                            'discount'                          => null,
                            'ended_at'                          => 1638434965,
                            'items'                             =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'                 => 'si_Kha9SuXRWJyImA',
                                                    'object'             => 'subscription_item',
                                                    'billing_thresholds' => null,
                                                    'created'            => 1638434962,
                                                    'metadata'           =>
                                                        [
                                                        ],
                                                    'plan'               =>
                                                        [
                                                            'id'                => 'plan_Kha9wkDRk6UyWi',
                                                            'object'            => 'plan',
                                                            'active'            => true,
                                                            'aggregate_usage'   => null,
                                                            'amount'            => 2000,
                                                            'amount_decimal'    => '2000',
                                                            'billing_scheme'    => 'per_unit',
                                                            'created'           => 1638434961,
                                                            'currency'          => 'usd',
                                                            'interval'          => 'month',
                                                            'interval_count'    => 1,
                                                            'livemode'          => false,
                                                            'metadata'          =>
                                                                [
                                                                ],
                                                            'nickname'          => null,
                                                            'product'           => 'prod_Kha9hjaq2URNG0',
                                                            'tiers_mode'        => null,
                                                            'transform_usage'   => null,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'price'              =>
                                                        [
                                                            'id'                  => 'plan_Kha9wkDRk6UyWi',
                                                            'object'              => 'price',
                                                            'active'              => true,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638434961,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [
                                                                ],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_Kha9hjaq2URNG0',
                                                            'recurring'           =>
                                                                [
                                                                    'aggregate_usage'   => null,
                                                                    'interval'          => 'month',
                                                                    'interval_count'    => 1,
                                                                    'trial_period_days' => null,
                                                                    'usage_type'        => 'licensed',
                                                                ],
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'recurring',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'quantity'           => 1,
                                                    'subscription'       => 'sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                                                    'tax_rates'          =>
                                                        [
                                                        ],
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/subscription_items?subscription=sub_1K2AykB9m4sTKy1q9qkQPiZ1',
                                ],
                            'latest_invoice'                    => 'in_1K2AykB9m4sTKy1q329h7jI1',
                            'livemode'                          => false,
                            'metadata'                          =>
                                [
                                ],
                            'next_pending_invoice_item_invoice' => null,
                            'pause_collection'                  => null,
                            'payment_settings'                  =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'pending_invoice_item_interval'     => null,
                            'pending_setup_intent'              => null,
                            'pending_update'                    => null,
                            'plan'                              =>
                                [
                                    'id'                => 'plan_Kha9wkDRk6UyWi',
                                    'object'            => 'plan',
                                    'active'            => true,
                                    'aggregate_usage'   => null,
                                    'amount'            => 2000,
                                    'amount_decimal'    => '2000',
                                    'billing_scheme'    => 'per_unit',
                                    'created'           => 1638434961,
                                    'currency'          => 'usd',
                                    'interval'          => 'month',
                                    'interval_count'    => 1,
                                    'livemode'          => false,
                                    'metadata'          =>
                                        [
                                        ],
                                    'nickname'          => null,
                                    'product'           => 'prod_Kha9hjaq2URNG0',
                                    'tiers_mode'        => null,
                                    'transform_usage'   => null,
                                    'trial_period_days' => null,
                                    'usage_type'        => 'licensed',
                                ],
                            'quantity'                          => 1,
                            'schedule'                          => null,
                            'start_date'                        => 1638434962,
                            'status'                            => 'canceled',
                            'transfer_data'                     => null,
                            'trial_end'                         => null,
                            'trial_start'                       => null,
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_F9X7ghFGQY4Cnt',
                    'idempotency_key' => null,
                ],
            'type'             => 'customer.subscription.deleted',
        ];

        // Send webhook
        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        $this->assertDatabaseHas('subscriptions', [
            'status'  => 'cancelled',
            'ends_at' => Carbon::createFromTimestamp(1641113362),
        ]);

        Event::assertDispatched(fn (SubscriptionWasCancelled $event) => $event->subscription->id === $subscription->id);
    }

    /**
     * @test
     */
    public function stripe_webhook_invoice_payment_succeeded()
    {
        $user = User::factory()
            ->create();

        Customer::create([
            'user_id'        => $user->id,
            'driver_user_id' => 'cus_KhYcsbpXlseQU0',
            'driver'         => 'stripe',
        ]);

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver'                 => 'stripe',
                'driver_subscription_id' => 'sub_1K9m2OB9m4sTKy1qS88pLbae',
            ])
            ->create([
                'user_id'    => $user->id,
                'status'     => 'active',
                'renews_at'  => null,
                'ends_at'    => null,
                'created_at' => now()->subDays(14),
            ]);

        resolve(GetSubscriptionStripeMocksClass::class)();

        $payload = [
            'id'               => 'evt_1K2BE3B9m4sTKy1qyZYi7bGJ',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638435910,
            'data'             =>
                [
                    'object' =>
                        [
                            'id'                               => 'in_1K2BE0B9m4sTKy1qiscPsYFt',
                            'object'                           => 'invoice',
                            'account_country'                  => 'SK',
                            'account_name'                     => 'VueFileManager V2',
                            'account_tax_ids'                  => null,
                            'amount_due'                       => 2000,
                            'amount_paid'                      => 2000,
                            'amount_remaining'                 => 0,
                            'application_fee_amount'           => null,
                            'attempt_count'                    => 1,
                            'attempted'                        => true,
                            'auto_advance'                     => false,
                            'automatic_tax'                    =>
                                [
                                    'enabled' => false,
                                    'status'  => null,
                                ],
                            'billing_reason'                   => 'subscription_create',
                            'charge'                           => 'ch_3K2BE0B9m4sTKy1q0QzCoA7r',
                            'collection_method'                => 'charge_automatically',
                            'created'                          => 1638435908,
                            'currency'                         => 'usd',
                            'custom_fields'                    => null,
                            'customer'                         => 'cus_KhYcsbpXlseQU0',
                            'customer_address'                 => null,
                            'customer_email'                   => null,
                            'customer_name'                    => null,
                            'customer_phone'                   => null,
                            'customer_shipping'                => null,
                            'customer_tax_exempt'              => 'none',
                            'customer_tax_ids'                 =>
                                [
                                ],
                            'default_payment_method'           => null,
                            'default_source'                   => null,
                            'default_tax_rates'                =>
                                [
                                ],
                            'description'                      => null,
                            'discount'                         => null,
                            'discounts'                        =>
                                [
                                ],
                            'due_date'                         => null,
                            'ending_balance'                   => 0,
                            'footer'                           => null,
                            'hosted_invoice_url'               => 'https://invoice.stripe.com/i/acct_1K1tczB9m4sTKy1q/test_YWNjdF8xSzF0Y3pCOW00c1RLeTFxLF9LaGFPTG55QWVhUUdPUExlQ1FTMUZSUnZXY3NqNkNu0100xdMvZ4rk',
                            'invoice_pdf'                      => 'https://pay.stripe.com/invoice/acct_1K1tczB9m4sTKy1q/test_YWNjdF8xSzF0Y3pCOW00c1RLeTFxLF9LaGFPTG55QWVhUUdPUExlQ1FTMUZSUnZXY3NqNkNu0100xdMvZ4rk/pdf',
                            'last_finalization_error'          => null,
                            'lines'                            =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'                => 'il_1K2BE0B9m4sTKy1qIulIv0wb',
                                                    'object'            => 'line_item',
                                                    'amount'            => 2000,
                                                    'currency'          => 'usd',
                                                    'description'       => '1 × myproduct (at $20.00 / month)',
                                                    'discount_amounts'  =>
                                                        [
                                                        ],
                                                    'discountable'      => true,
                                                    'discounts'         =>
                                                        [
                                                        ],
                                                    'livemode'          => false,
                                                    'metadata'          =>
                                                        [
                                                        ],
                                                    'period'            =>
                                                        [
                                                            'end'   => 1641114308,
                                                            'start' => 1638435908,
                                                        ],
                                                    'plan'              =>
                                                        [
                                                            'id'                => 'plan_KhaOxXzYP2Nova',
                                                            'object'            => 'plan',
                                                            'active'            => true,
                                                            'aggregate_usage'   => null,
                                                            'amount'            => 2000,
                                                            'amount_decimal'    => '2000',
                                                            'billing_scheme'    => 'per_unit',
                                                            'created'           => 1638435907,
                                                            'currency'          => 'usd',
                                                            'interval'          => 'month',
                                                            'interval_count'    => 1,
                                                            'livemode'          => false,
                                                            'metadata'          =>
                                                                [
                                                                ],
                                                            'nickname'          => null,
                                                            'product'           => 'prod_KhaO4s6RUDhOWF',
                                                            'tiers_mode'        => null,
                                                            'transform_usage'   => null,
                                                            'trial_period_days' => null,
                                                            'usage_type'        => 'licensed',
                                                        ],
                                                    'price'             =>
                                                        [
                                                            'id'                  => 'plan_KhaOxXzYP2Nova',
                                                            'object'              => 'price',
                                                            'active'              => true,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638435907,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [
                                                                ],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_KhaO4s6RUDhOWF',
                                                            'recurring'           =>
                                                                [
                                                                    'aggregate_usage'   => null,
                                                                    'interval'          => 'month',
                                                                    'interval_count'    => 1,
                                                                    'trial_period_days' => null,
                                                                    'usage_type'        => 'licensed',
                                                                ],
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'recurring',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'proration'         => false,
                                                    'quantity'          => 1,
                                                    'subscription'      => 'sub_1K2BE0B9m4sTKy1qJDq7pt1o',
                                                    'subscription_item' => 'si_KhaOoyIprhNoTc',
                                                    'tax_amounts'       =>
                                                        [
                                                        ],
                                                    'tax_rates'         =>
                                                        [
                                                        ],
                                                    'type'              => 'subscription',
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/invoices/in_1K2BE0B9m4sTKy1qiscPsYFt/lines',
                                ],
                            'livemode'                         => false,
                            'metadata'                         =>
                                [
                                ],
                            'next_payment_attempt'             => null,
                            'number'                           => 'D587892A-0004',
                            'on_behalf_of'                     => null,
                            'paid'                             => true,
                            'payment_intent'                   => 'pi_3K2BE0B9m4sTKy1q0lVmO6zL',
                            'payment_settings'                 =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'period_end'                       => 1638435908,
                            'period_start'                     => 1638435908,
                            'post_payment_credit_notes_amount' => 0,
                            'pre_payment_credit_notes_amount'  => 0,
                            'quote'                            => null,
                            'receipt_number'                   => null,
                            'starting_balance'                 => 0,
                            'statement_descriptor'             => null,
                            'status'                           => 'paid',
                            'status_transitions'               =>
                                [
                                    'finalized_at'            => 1638435908,
                                    'marked_uncollectible_at' => null,
                                    'paid_at'                 => 1638435908,
                                    'voided_at'               => null,
                                ],
                            'subscription'                     => $subscription->driverId(),
                            'subtotal'                         => 2000,
                            'tax'                              => null,
                            'total'                            => 2000,
                            'total_discount_amounts'           =>
                                [
                                ],
                            'total_tax_amounts'                =>
                                [
                                ],
                            'transfer_data'                    => null,
                            'webhooks_delivered_at'            => null,
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_Zsggsww04wUoXv',
                    'idempotency_key' => 'ef9ba391-b6b4-40bc-95ec-3a9373ffd0e7',
                ],
            'type'             => 'invoice.payment_succeeded',
        ];

        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        $this
            ->assertDatabaseHas('subscriptions', [
                'renews_at' => '2022-01-23 07:48:32',
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'note'      => $subscription->name,
                'currency'  => 'usd',
                'amount'    => 20,
                'driver'    => 'stripe',
                'reference' => 'in_1K2BE0B9m4sTKy1qiscPsYFt',
            ]);
    }

    /**
     * @test
     */
    public function stripe_webhook_payment_intent_payment_failed()
    {
        $user = User::factory()
            ->create();

        Customer::create([
            'user_id'        => $user->id,
            'driver_user_id' => 'cus_Khwr2RAte5Xhkf',
            'driver'         => 'stripe',
        ]);

        $payload = [
            'id'               => 'evt_1K2Wy7B9m4sTKy1qGnhJdnMB',
            'object'           => 'event',
            'api_version'      => '2020-08-27',
            'created'          => 1638519490,
            'data'             =>
                [
                    'object' =>
                        [
                            'id'                               => 'in_1K2Wy0B9m4sTKy1qjxX0hq75',
                            'object'                           => 'invoice',
                            'account_country'                  => 'SK',
                            'account_name'                     => 'VueFileManager V2',
                            'account_tax_ids'                  => null,
                            'amount_due'                       => 2000,
                            'amount_paid'                      => 0,
                            'amount_remaining'                 => 2000,
                            'application_fee_amount'           => null,
                            'attempt_count'                    => 1,
                            'attempted'                        => true,
                            'auto_advance'                     => true,
                            'automatic_tax'                    =>
                                [
                                    'enabled' => false,
                                    'status'  => null,
                                ],
                            'billing_reason'                   => 'manual',
                            'charge'                           => 'ch_3K2Wy4B9m4sTKy1q0CP77rwq',
                            'collection_method'                => 'charge_automatically',
                            'created'                          => 1638519484,
                            'currency'                         => 'usd',
                            'custom_fields'                    => null,
                            'customer'                         => 'cus_Khwr2RAte5Xhkf',
                            'customer_address'                 => null,
                            'customer_email'                   => null,
                            'customer_name'                    => null,
                            'customer_phone'                   => null,
                            'customer_shipping'                => null,
                            'customer_tax_exempt'              => 'none',
                            'customer_tax_ids'                 =>
                                [],
                            'default_payment_method'           => null,
                            'default_source'                   => null,
                            'default_tax_rates'                =>
                                [],
                            'description'                      => '(created by Stripe CLI)',
                            'discount'                         => null,
                            'discounts'                        =>
                                [],
                            'due_date'                         => null,
                            'ending_balance'                   => 0,
                            'footer'                           => null,
                            'hosted_invoice_url'               => 'https://invoice.stripe.com/i/acct_1K1tczB9m4sTKy1q/test_YWNjdF8xSzF0Y3pCOW00c1RLeTFxLF9LaHdyMnlFb1QwUHd5dmN1UXF1T2VNWTRqallyT0Uy01009O1m7XuZ',
                            'invoice_pdf'                      => 'https://pay.stripe.com/invoice/acct_1K1tczB9m4sTKy1q/test_YWNjdF8xSzF0Y3pCOW00c1RLeTFxLF9LaHdyMnlFb1QwUHd5dmN1UXF1T2VNWTRqallyT0Uy01009O1m7XuZ/pdf',
                            'last_finalization_error'          => null,
                            'lines'                            =>
                                [
                                    'object'      => 'list',
                                    'data'        =>
                                        [
                                            0 =>
                                                [
                                                    'id'               => 'il_1K2WxzB9m4sTKy1qsEvuturI',
                                                    'object'           => 'line_item',
                                                    'amount'           => 2000,
                                                    'currency'         => 'usd',
                                                    'description'      => '(created by Stripe CLI)',
                                                    'discount_amounts' =>
                                                        [],
                                                    'discountable'     => true,
                                                    'discounts'        =>
                                                        [],
                                                    'invoice_item'     => 'ii_1K2WxzB9m4sTKy1qMC7pzti4',
                                                    'livemode'         => false,
                                                    'metadata'         =>
                                                        [],
                                                    'period'           =>
                                                        [
                                                            'end'   => 1638519483,
                                                            'start' => 1638519483,
                                                        ],
                                                    'plan'             => null,
                                                    'price'            =>
                                                        [
                                                            'id'                  => 'price_1K2WxzB9m4sTKy1qQi66XWt1',
                                                            'object'              => 'price',
                                                            'active'              => false,
                                                            'billing_scheme'      => 'per_unit',
                                                            'created'             => 1638519483,
                                                            'currency'            => 'usd',
                                                            'livemode'            => false,
                                                            'lookup_key'          => null,
                                                            'metadata'            =>
                                                                [],
                                                            'nickname'            => null,
                                                            'product'             => 'prod_Khwrczl0xm0c2y',
                                                            'recurring'           => null,
                                                            'tax_behavior'        => 'unspecified',
                                                            'tiers_mode'          => null,
                                                            'transform_quantity'  => null,
                                                            'type'                => 'one_time',
                                                            'unit_amount'         => 2000,
                                                            'unit_amount_decimal' => '2000',
                                                        ],
                                                    'proration'        => false,
                                                    'quantity'         => 1,
                                                    'subscription'     => null,
                                                    'tax_amounts'      =>
                                                        [],
                                                    'tax_rates'        =>
                                                        [],
                                                    'type'             => 'invoiceitem',
                                                ],
                                        ],
                                    'has_more'    => false,
                                    'total_count' => 1,
                                    'url'         => '/v1/invoices/in_1K2Wy0B9m4sTKy1qjxX0hq75/lines',
                                ],
                            'livemode'                         => false,
                            'metadata'                         =>
                                [],
                            'next_payment_attempt'             => null,
                            'number'                           => 'D587892A-0020',
                            'on_behalf_of'                     => null,
                            'paid'                             => false,
                            'payment_intent'                   => 'pi_3K2Wy4B9m4sTKy1q07b3xI2o',
                            'payment_settings'                 =>
                                [
                                    'payment_method_options' => null,
                                    'payment_method_types'   => null,
                                ],
                            'period_end'                       => 1638519484,
                            'period_start'                     => 1638519484,
                            'post_payment_credit_notes_amount' => 0,
                            'pre_payment_credit_notes_amount'  => 0,
                            'quote'                            => null,
                            'receipt_number'                   => null,
                            'starting_balance'                 => 0,
                            'statement_descriptor'             => null,
                            'status'                           => 'open',
                            'status_transitions'               =>
                                [
                                    'finalized_at'            => 1638519488,
                                    'marked_uncollectible_at' => null,
                                    'paid_at'                 => null,
                                    'voided_at'               => null,
                                ],
                            'subscription'                     => null,
                            'subtotal'                         => 2000,
                            'tax'                              => null,
                            'total'                            => 2000,
                            'total_discount_amounts'           =>
                                [],
                            'total_tax_amounts'                =>
                                [],
                            'transfer_data'                    => null,
                            'webhooks_delivered_at'            => 1638519485,
                        ],
                ],
            'livemode'         => false,
            'pending_webhooks' => 2,
            'request'          =>
                [
                    'id'              => 'req_EsVcw3nL3ntpl9',
                    'idempotency_key' => '6b9a32b7-1a76-48c1-8a29-39ec741a7d5e',
                ],
            'type'             => 'invoice.payment_action_required',
        ];

        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        Notification::assertSentTo($user, ConfirmStripePaymentNotification::class);
    }

    /**
     * @test
     */
    public function stripe_webhook_payment_method_attached()
    {
        $user = User::factory()
            ->create();

        Customer::create([
            'user_id'        => $user->id,
            'driver_user_id' => 'cus_KrgRc2TH3yh3xC',
            'driver'         => 'stripe',
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

        $this->assertDatabaseHas('credit_cards', [
            'user_id'    => $user->id,
            'brand'      => 'visa',
            'last4'      => '4242',
            'expiration' => '2022-08-01 00:00:00',
            'reference'  => 'pm_00000000000000',
            'service'    => 'stripe',
        ]);
    }
}
