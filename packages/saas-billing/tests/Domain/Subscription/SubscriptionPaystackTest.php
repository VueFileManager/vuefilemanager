<?php
namespace Tests\Domain\Subscription;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Http;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;

class SubscriptionPaystackTest extends TestCase
{
    /**
     * @test
     */
    public function it_cancel_paystack_subscription()
    {
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

        $ends_at = now()->addDays(14);

        Http::fake([
            "https://api.paystack.co/subscription/{$subscription->driverId()}" => Http::response([
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
                                0 => [
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
                    'subscription_code' => $subscription->driverId(),
                    'email_token'       => 'd7gofp6yppn3qz7',
                    'easy_cron_id'      => null,
                    'cron_expression'   => '0 0 28 * *',
                    'next_payment_date' => $ends_at,
                    'open_invoice'      => null,
                    'id'                => 9,
                    'createdAt'         => '2016-03-30T00:01:04.000Z',
                    'updatedAt'         => '2016-03-30T00:22:58.000Z',
                ],
            ]),
            'https://api.paystack.co/subscription/disable'                     => Http::response([
                'status'  => true,
                'message' => 'Subscription disabled successfully',
            ]),
        ]);

        $this
            ->actingAs($user)
            ->postJson('/api/subscriptions/cancel')
            ->assertOk()
            ->assertJsonFragment([
                'status' => 'cancelled',
            ]);

        $subscription->refresh();

        $this
            ->assertDatabaseHas('subscriptions', [
                'status'  => 'cancelled',
                'ends_at' => $ends_at,
            ])
            ->assertEquals(true, $subscription->onGracePeriod());

        Http::assertSentCount(2);
    }

    /**
     * @test
     */
    public function it_generate_update_link_for_paystack_subscription()
    {
        $user = User::factory()
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paystack',
            ])
            ->create([
                'user_id'    => $user->id,
                'status'     => 'active',
            ]);

        Http::fake([
            "https://api.paystack.co/subscription/{$subscription->driverId()}/manage/link" => Http::response([
                'status'  => true,
                'message' => 'Link generated',
                'data'    => [
                    'link' => 'https://paystack.com/manage/subscriptions/qlgwhpyq1ts9nsw?subscription_token=ugly-long-token',
                ],
            ]),
        ]);

        $this
            ->actingAs($user)
            ->postJson("/api/subscriptions/edit/{$subscription->id}")
            ->assertCreated()
            ->assertJsonFragment([
                'driver' => 'paystack',
                'url'    => 'https://paystack.com/manage/subscriptions/qlgwhpyq1ts9nsw?subscription_token=ugly-long-token',
            ]);

        Http::assertSentCount(1);
    }

    /**
     * TODO: documented but not working on api side
     */
    public function it_resume_paystack_subscription()
    {
        $user = User::factory()
            ->create();

        $subscription = Subscription::factory()
            ->hasDriver([
                'driver' => 'paystack',
            ])
            ->create([
                'user_id'    => $user->id,
                'status'     => 'cancelled',
                'ends_at'    => now()->addDays(7),
                'created_at' => now()->subDays(14),
            ]);

        Http::fake([
            "https://api.paystack.co/subscription/{$subscription->driverId()}" => Http::response([
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
                                0 => [
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
                    'status'            => 'disabled',
                    'quantity'          => 1,
                    'amount'            => 50000,
                    'subscription_code' => $subscription->driverId(),
                    'email_token'       => 'd7gofp6yppn3qz7',
                    'easy_cron_id'      => null,
                    'cron_expression'   => '0 0 28 * *',
                    'next_payment_date' => now()->addDays(7),
                    'open_invoice'      => null,
                    'id'                => 9,
                    'createdAt'         => '2016-03-30T00:01:04.000Z',
                    'updatedAt'         => '2016-03-30T00:22:58.000Z',
                ],
            ]),
            'https://api.paystack.co/subscription/enable'                      => Http::response([
                'status'  => true,
                'message' => 'Subscription enabled successfully',
            ]),
        ]);

        $this
            ->actingAs($user)
            ->postJson('/api/subscriptions/resume')
            ->assertOk()
            ->assertJsonFragment([
                'status' => 'active',
            ]);

        $subscription->refresh();

        $this
            ->assertDatabaseHas('subscriptions', [
                'status'  => 'active',
                'ends_at' => null,
            ])
            ->assertEquals(false, $subscription->onGracePeriod());
    }
}
