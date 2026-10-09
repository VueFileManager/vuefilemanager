<?php
namespace Tests\Support\Middleware;

use Tests\TestCase;
use Tests\Models\User;

class MiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function it_try_to_get_admin_role_as_user()
    {
        $user = User::factory()
            ->create([
                'role' => 'user',
            ]);

        $this
            ->actingAs($user)
            ->get('/api/subscriptions/admin')
            ->assertForbidden();
    }
}
