<?php
namespace Tests\Domain\Admin;

use Tests\TestCase;
use App\Users\Models\User;
use Domain\Files\Models\File;

class DashboardTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_dashboard_data()
    {
        $user = User::factory()
            ->create(['role' => 'admin']);

        File::factory()
            ->count(2)
            ->create(['filesize' => 1000000]);

        $this
            ->actingAs($user)
            ->getJson('/api/admin/dashboard')
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_new_users_for_dashboard()
    {
        $users = User::factory()
            ->hasSettings()
            ->count(3)
            ->create(['role' => 'user']);

        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $users->each(
            fn ($user) => $this
                ->actingAs($admin)
                ->getJson('/api/admin/dashboard/newbies')
                ->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user->id,
                ])
        );
    }
}
