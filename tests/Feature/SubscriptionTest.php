<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserSettings;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Subscription;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    public function __construct()
    {
        parent::__construct();
        //$this->subscription = app()->make(StripeService::class);
    }

    /**
     *
     */
    public function it_get_setup_intent()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->getJson('/api/user/subscription/setup-intent')
            ->assertStatus(200)
            ->assertJsonFragment([
                "object" => "setup_intent"
            ]);

        $this->assertDatabaseMissing('users', [
            'stripe_id' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_upgrade_plan()
    {
        $user = User::factory(User::class)
            ->create([
                'email'          => 'howdy@hi5ve.digital',
                'stripe_id'      => 'cus_HgbhvNCbSwV8Qg',
                'card_brand'     => 'visa',
                'card_last_four' => 4242,
            ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => [
                'billing_address'      => $user->settings->address,
                'billing_city'         => $user->settings->city,
                'billing_country'      => $user->settings->country,
                'billing_name'         => $user->settings->name,
                'billing_phone_number' => $user->settings->phone_number,
                'billing_postal_code'  => $user->settings->postal_code,
                'billing_state'        => $user->settings->state,
            ],
            'payment' => [
                'type' => 'stripe',
            ],
            'plan'    => [
                'data' => [
                    'id'         => "business-pack",
                    'type'       => "plans",
                    'attributes' => [
                        'name'               => "Business Packs",
                        'description'        => "When your business start grow up.",
                        'price'              => "$44.99",
                        'capacity'           => 1000,
                        'capacity_formatted' => "1TB",
                        'currency'           => "USD",
                        'tax_rates'          => [],
                    ],
                ],
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('subscriptions', [
            'stripe_status' => 'active'
        ]);

        $this->assertDatabaseHas('user_settings', [
            'storage_capacity' => 1000
        ]);
    }

    /**
     * @test
     */
    public function it_cancel_subscription()
    {
        $user = User::factory(User::class)
            ->create([
                'email'          => 'howdy@hi5ve.digital',
                'stripe_id'      => 'cus_HgbhvNCbSwV8Qg',
                'card_brand'     => 'visa',
                'card_last_four' => 4242,
            ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => [
                'billing_address'      => $user->settings->address,
                'billing_city'         => $user->settings->city,
                'billing_country'      => $user->settings->country,
                'billing_name'         => $user->settings->name,
                'billing_phone_number' => $user->settings->phone_number,
                'billing_postal_code'  => $user->settings->postal_code,
                'billing_state'        => $user->settings->state,
            ],
            'payment' => [
                'type' => 'stripe',
            ],
            'plan'    => [
                'data' => [
                    'id'         => "business-pack",
                    'type'       => "plans",
                    'attributes' => [
                        'name'               => "Business Packs",
                        'description'        => "When your business start grow up.",
                        'price'              => "$44.99",
                        'capacity'           => 1000,
                        'capacity_formatted' => "1TB",
                        'currency'           => "USD",
                        'tax_rates'          => [],
                    ],
                ],
            ],
        ])->assertStatus(204);

        $this->postJson('/api/user/subscription/cancel')
            ->assertStatus(204);

        $this->assertDatabaseMissing('subscriptions', [
            'ends_at' => 'canceled'
        ]);
    }
}
