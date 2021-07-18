<?php
namespace Tests\Domain\Plans;

use Tests\TestCase;
use App\Users\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class PlansTest extends TestCase
{
    /**
     *
     */
    public function it_get_all_plans_for_index_page()
    {
        $this->getJson('/api/pricing')
            ->assertStatus(200);
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
                'price'       => (string) rand(1, 99),
                'description' => 'Some random description',
                'capacity'    => rand(1, 999),
            ],
        ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'name' => $plan_name,
            ]);

        $this->deleteJson('/api/admin/plans/' . strtolower($plan_name))
            ->assertStatus(204);
    }
}
