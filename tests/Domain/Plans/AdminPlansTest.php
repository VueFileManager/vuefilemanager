<?php
namespace Tests\Domain\Plans;

use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class AdminPlansTest extends TestCase
{
    /**
     *
     */
    public function it_get_single_plan_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/plans/' . $this->plan['data']['id'])
            ->assertStatus(200);
    }

    /**
     *
     */
    public function it_create_single_plan_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $plan_name = 'test-plan-' . Str::random(16);

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/plans', [
                'type'       => 'plan',
                'attributes' => [
                    'name'        => $plan_name,
                    'price'       => (string) rand(1, 99),
                    'description' => 'Some random description',
                    'capacity'    => rand(1, 999),
                ],
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $plan_name,
            ]);
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
                'price'       => (string) rand(1, 99),
                'description' => 'Some random description',
                'capacity'    => rand(1, 999),
            ],
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $plan_name,
            ]);

        $this->patchJson('/api/admin/plans/' . strtolower($plan_name), [
            'name'  => 'description',
            'value' => 'updated description',
        ])->assertStatus(201);
    }

    /**
     *
     */
    public function it_get_subscribers_from_plan_from_admin()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        $this
            ->actingAs($user)
            ->postJson('/api/user/subscription/upgrade', [
                'billing' => $this->billing,
                'plan'    => $this->plan,
                'payment' => [
                    'type' => 'stripe',
                ],
            ])->assertStatus(204);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/plans/' . $this->plan['data']['id'] . '/subscribers')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
            ]);
    }

    /**
     *
     */
    public function it_get_all_invoices_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/invoices')
            ->assertStatus(200);
    }

    /**
     *
     */
    public function it_get_single_user_invoice_page_from_admin()
    {
        $user = User::factory(User::class)
            ->create($this->user);

        $invoices = $this
            ->actingAs($user)
            ->getJson('/api/user/invoices')
            ->assertStatus(200)
            ->assertJsonFragment([
                'customer' => $this->user['stripe_id'],
            ]);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $invoice_id = json_decode($invoices->content(), true)['data'][0]['data']['id'];

        $this
            ->actingAs($admin)
            ->get("/invoice/{$this->user['stripe_id']}/$invoice_id")
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

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson("/api/admin/users/$user->id/invoices")
            ->assertStatus(200)
            ->assertJsonFragment([
                'customer' => $this->user['stripe_id'],
            ]);
    }

    /**
     *
     */
    public function it_get_all_plans_from_admin()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/plans')
            ->assertStatus(200);
    }
}
