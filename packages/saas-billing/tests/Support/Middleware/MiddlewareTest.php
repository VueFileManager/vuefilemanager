<?php
namespace Tests\Support\Middleware;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Models\User;

class MiddlewareTest extends TestCase
{
    #[Test]
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
