<?php

namespace Tests\Feature\External;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    private $user;

    private $plan;

    private $billing;

    public function __construct()
    {
        parent::__construct();

        // Define test user
        $this->user = [
            'email'          => 'howdy@hi5ve.digital',
            'stripe_id'      => 'cus_HgbhvNCbSwV8Qg',
            'card_brand'     => 'visa',
            'card_last_four' => 4242,
        ];

        // Define test plan to subscribe
        $this->plan = [
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
     *
     */
    public function it_upgrade_plan()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
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
     *
     */
    public function it_cancel_subscription()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
            ],
        ])->assertStatus(204);

        $this->postJson('/api/user/subscription/cancel')
            ->assertStatus(204);

        $this->assertDatabaseMissing('subscriptions', [
            'ends_at' => null
        ]);
    }

    /**
     *
     */
    public function it_resume_subscription()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
            ],
        ])->assertStatus(204);

        $this->postJson('/api/user/subscription/cancel')
            ->assertStatus(204);

        $this->postJson('/api/user/subscription/resume')
            ->assertStatus(204);

        $this->assertDatabaseHas('subscriptions', [
            'ends_at' => null
        ]);
    }

    /**
     *
     */
    public function it_get_user_subscription_details()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
            ],
        ])->assertStatus(204);

        $this->getJson('/api/user/subscription')
            ->assertStatus(200)
            ->assertExactJson([
                "data" => [
                    "id"         => "business-pack",
                    "type"       => "subscription",
                    "attributes" => [
                        "incomplete"         => false,
                        "active"             => true,
                        "canceled"           => false,
                        "name"               => "Business Packs",
                        "capacity"           => 1000,
                        "capacity_formatted" => "1TB",
                        "slug"               => "business-pack",
                        "canceled_at"        => format_date(Carbon::now(), '%d. %B. %Y'),
                        "created_at"         => format_date(Carbon::now(), '%d. %B. %Y'),
                        "ends_at"            => format_date(Carbon::now()->addMonth(), '%d. %B. %Y'),
                    ]
                ]
            ]);
    }

    /**
     *
     */
    public function it_get_user_invoices()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
            ],
        ])->assertStatus(204);

        $this->getJson('/api/user/invoices')
            ->assertStatus(200)
            ->assertJsonFragment([
                'customer' => $this->user['stripe_id']
            ]);
    }

    /**
     *
     */
    public function it_get_user_subscription_from_admin()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
            ],
        ])->assertStatus(204);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson("/api/admin/users/$user->id/subscription")
            ->assertStatus(200)
            ->assertExactJson([
                "data" => [
                    "id"         => "business-pack",
                    "type"       => "subscription",
                    "attributes" => [
                        "incomplete"         => false,
                        "active"             => true,
                        "canceled"           => false,
                        "name"               => "Business Packs",
                        "capacity"           => 1000,
                        "capacity_formatted" => "1TB",
                        "slug"               => "business-pack",
                        "canceled_at"        => format_date(Carbon::now(), '%d. %B. %Y'),
                        "created_at"         => format_date(Carbon::now(), '%d. %B. %Y'),
                        "ends_at"            => format_date(Carbon::now()->addMonth(), '%d. %B. %Y'),
                    ]
                ]
            ]);
    }

    /**
     *
     */
    public function it_store_stripe_plans_via_setup_wizard()
    {
        $this->postJson('/api/setup/stripe-plans', [
            'plans' => [
                [
                    'type'       => 'plan',
                    'attributes' => [
                        'name'        => 'test-plan-' . Str::random(16),
                        'price'       => (string)rand(1, 99),
                        'description' => 'Some random description',
                        'capacity'    => rand(1, 999),
                    ],
                ],
                [
                    'type'       => 'plan',
                    'attributes' => [
                        'name'        => 'test-plan-' . Str::random(16),
                        'price'       => (string)rand(1, 99),
                        'description' => 'Some random description',
                        'capacity'    => rand(1, 999),
                    ],
                ],
            ]
        ])->assertStatus(204);
    }

    /**
     *
     */
    public function it_delete_single_plan()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $plan_name = 'test-plan-' . Str::random(16);

        $this->postJson('/api/admin/plans', [
            'type'       => 'plan',
            'attributes' => [
                'name'        => $plan_name,
                'price'       => (string)rand(1, 99),
                'description' => 'Some random description',
                'capacity'    => rand(1, 999),
            ],
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $plan_name
            ]);

        $this->deleteJson("/api/admin/plans/" . strtolower($plan_name))
            ->assertStatus(204);
    }

    /**
     *
     */
    public function it_update_single_plan_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $plan_name = 'test-plan-' . Str::random(16);

        $this->postJson('/api/admin/plans', [
            'type'       => 'plan',
            'attributes' => [
                'name'        => $plan_name,
                'price'       => (string)rand(1, 99),
                'description' => 'Some random description',
                'capacity'    => rand(1, 999),
            ],
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $plan_name
            ]);

        $this->patchJson("/api/admin/plans/" . strtolower($plan_name), [
            'name'  => 'description',
            'value' => 'updated description'
        ])->assertStatus(201);
    }

    /**
     *
     */
    public function it_get_subscribers_from_plan_from_admin()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $this->postJson('/api/user/subscription/upgrade', [
            'billing' => $this->billing,
            'plan'    => $this->plan,
            'payment' => [
                'type' => 'stripe',
            ],
        ])->assertStatus(204);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/plans/' . $this->plan['data']['id'] . '/subscribers')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id
            ]);
    }

    /**
     *
     */
    public function it_get_all_invoices_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson("/api/admin/invoices")
            ->assertStatus(200);
    }

    /**
     *
     */
    public function it_get_single_user_invoice_page_from_admin()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $invoices = $this->getJson('/api/user/invoices')
            ->assertStatus(200)
            ->assertJsonFragment([
                'customer' => $this->user['stripe_id']
            ]);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $invoice_id = json_decode($invoices->content(), true)['data'][0]['data']['id'];

        $this->get("/invoice/{$this->user['stripe_id']}/$invoice_id")
            ->assertStatus(200)
            ->assertSee('Invoice');
    }

    /**
     *
     */
    public function it_get_user_invoices_from_admin()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        Sanctum::actingAs($user);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson("/api/admin/users/$user->id/invoices")
            ->assertStatus(200)
            ->assertJsonFragment([
                'customer' => $this->user['stripe_id']
            ]);
    }

    /**
     *
     */
    public function it_get_all_plans_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/plans')
            ->assertStatus(200);
    }

    /**
     *
     */
    public function it_get_single_plan_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/plans/' . $this->plan['data']['id'])
            ->assertStatus(200);
    }

    /**
     *
     */
    public function it_create_single_plan_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $plan_name = 'test-plan-' . Str::random(16);

        $this->postJson('/api/admin/plans', [
            'type'       => 'plan',
            'attributes' => [
                'name'        => $plan_name,
                'price'       => (string)rand(1, 99),
                'description' => 'Some random description',
                'capacity'    => rand(1, 999),
            ],
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $plan_name
            ]);
    }
}
