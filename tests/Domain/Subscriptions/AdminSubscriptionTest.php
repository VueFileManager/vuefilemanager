<?php
namespace Tests\Domain\Subscriptions;

use Tests\TestCase;
use App\Users\Models\User;

class AdminSubscriptionTest extends TestCase
{
    /**
     *
     */
    public function it_get_user_subscription_from_admin()
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
            ->getJson("/api/admin/users/$user->id/subscription")
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id'         => 'business-pack',
                    'type'       => 'subscription',
                    'attributes' => [
                        'incomplete'         => false,
                        'active'             => true,
                        'canceled'           => false,
                        'name'               => 'Business Packs',
                        'capacity'           => 1000,
                        'capacity_formatted' => '1TB',
                        'slug'               => 'business-pack',
                        'canceled_at'        => format_date(now(), '%d. %B. %Y'),
                        'created_at'         => format_date(now(), '%d. %B. %Y'),
                        'ends_at'            => format_date(now()->addMonth(), '%d. %B. %Y'),
                    ],
                ],
            ]);
    }
}
