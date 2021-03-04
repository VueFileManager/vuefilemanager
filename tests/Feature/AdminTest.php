<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Setting;
use App\Models\User;
use DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Stripe\Subscription;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use DatabaseMigrations;

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

        Setting::create([
            'name'  => 'license',
            'value' => 'Regular'
        ]);

        DB::table('subscriptions')
            ->insert([
                'user_id'       => $user->id,
                'name'          => 'main',
                'stripe_id'     => 'sub_Hp4jgdIpPDDWXw',
                'stripe_status' => 'active',
            ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/admin/dashboard')
            ->assertStatus(200)
            ->assertExactJson([
                "license"             => "Regular",
                "app_version"         => config('vuefilemanager.version'),
                "total_users"         => 1,
                "total_used_space"    => "2.00MB",
                "total_premium_users" => 1,
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
