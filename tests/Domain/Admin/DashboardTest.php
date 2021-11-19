<?php
namespace Tests\Domain\Admin;

use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Settings\Models\Setting;

class DashboardTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_dashboard_data()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'admin']);

        File::factory(File::class)
            ->count(2)
            ->create(['filesize' => 1000000]);

        Setting::forceCreate([
            'name'  => 'license',
            'value' => 'Regular',
        ]);

        $this
            ->actingAs($user)
            ->getJson('/api/admin/dashboard')
            ->assertStatus(200)
            ->assertExactJson([
                'license'             => 'Regular',
                'app_version'         => config('vuefilemanager.version'),
                'total_users'         => 1,
                'total_used_space'    => '2.00MB',
            ]);
    }

    /**
     * @test
     */
    public function it_get_new_users_for_dashboard()
    {
        $users = User::factory(User::class)
            ->count(5)
            ->create(['role' => 'user']);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $users->each(function ($user) {
            $this->getJson('/api/admin/dashboard/newbies')
                ->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user->id,
                ]);
        });
    }
}
