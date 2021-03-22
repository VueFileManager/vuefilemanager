<?php

namespace Tests\Feature\Oasis;

use App\Models\User;
use Cartalyst\Stripe\Stripe;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Cashier\Subscription;
use Tests\TestCase;

class OasisSubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    private $plan;

    private $billing;

    public function __construct()
    {
        parent::__construct();

        // Define test plan to subscribe
        $this->plan = [
            'data' => [
                'id'         => "virtualni-sanon-basic",
                'type'       => "plans",
                'attributes' => [
                    'name'               => "Virtuální šanon BASIC",
                    'description'        => "Obsahuje 5 GB pro Vaše firemní data, 577 Kč (bez DPH). Cena zahrnuje i pojištění v hodnotě 50 Kč (pojištění obsahuje možnost obnovy dat z minulosti - verzování)",
                    'price'              => "CZK 699.00",
                    'capacity'           => 50,
                    'capacity_formatted' => "50GB",
                    'currency'           => "CZK",
                    'tax_rates'          => [],
                ],
            ],
        ];

        // Define test billing to subscribe
        $this->billing = [
            'billing_address'      => '2794 Alfreda Mount Suite 467 East Crystalberg',
            'billing_city'         => 'Christianfort',
            'billing_country'      => 'SK',
            'billing_name'         => 'Heidi Romaguera PhD',
            'billing_phone_number' => '+421',
            'billing_postal_code'  => '59445',
            'billing_state'        => 'SK',
        ];
    }

    /**
     * @test
     */
    public function it_get_subscription_request_details()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $user
            ->subscriptionRequest()
            ->create([
                'requested_plan' => 'virtualni-sanon-basic',
                'creator'        => 'john@doe.com',
            ]);

        $this->getJson("/api/oasis/subscription-request/{$user->subscriptionRequest->id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id'             => $user->subscriptionRequest->id,
                'requested_plan' => 'virtualni-sanon-basic',
            ]);
    }

    /**
     * @test
     */
    public function it_subscribe_user_and_pay_for_it()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $user
            ->subscriptionRequest()
            ->create([
                'requested_plan' => 'virtualni-sanon-basic',
                'creator'        => 'john@doe.com',
            ]);

        // Register payment method
        $stripe = Stripe::make(config('cashier.secret'), '2020-03-02');

        // Create test payment method
        $paymentMethod = $stripe->paymentMethods()->create([
            'type' => 'card',
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 11,
                'exp_year'  => 2022,
                'cvc'       => '123'
            ],
        ]);

        // Create stripe customer
        $user->createOrGetStripeCustomer();

        $this->postJson("/api/oasis/subscribe/{$user->subscriptionRequest->id}", [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'meta' => [
                    'pm' => $paymentMethod['id']
                ],
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('subscriptions', [
            'stripe_status' => 'active'
        ]);

        $this->assertDatabaseHas('user_settings', [
            'storage_capacity'   => 50,
            'payment_activation' => 1,
        ]);

        $this->assertDatabaseMissing('users', [
            'stripe_id'  => null,
            'card_brand' => null,
        ]);
    }
}
