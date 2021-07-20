<?php
namespace Tests\Domain\Invoices;

use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;

class UserInvoicesTest extends TestCase
{
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

        $this->getJson('/api/user/subscription/invoices')
            ->assertStatus(200)
            ->assertJsonFragment([
                'customer' => $this->user['stripe_id'],
            ]);
    }
}
