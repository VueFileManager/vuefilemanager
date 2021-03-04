<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
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
                "license"             => 'Regular',
                "app_version"         => config('vuefilemanager.version'),
                "total_users"         => 1,
                "total_used_space"    => '2.00MB',
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

    /**
     * @test
     */
    public function it_get_all_users()
    {
        $users = User::factory(User::class)
            ->count(5)
            ->create(['role' => 'user']);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $users->each(function ($user) {
            $this->getJson('/api/admin/users?page=1')
                ->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user->id,
                ]);
        });
    }

    /**
     * @test
     */
    public function it_get_single_user()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        // TODO: pridat exactjson po refaktorovani userresource
        $this->getJson("/api/admin/users/$user->id/detail")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
            ]);
    }


    /**
     * @test
     */
    public function it_get_non_existed_user_subscription()
    {
        $user = User::factory(User::class)
            ->create();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson("/api/admin/users/$user->id/subscription")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_get_user_storage_detail()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        collect(['image', 'audio', 'video', 'pdf', 'zip'])
            ->each(function ($mimetype) use ($user) {
                File::factory(File::class)
                    ->create([
                        'user_id'  => $user->id,
                        'type'     => $mimetype,
                        'mimetype' => $mimetype,
                        'filesize' => 1000000,
                    ]);
            });

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson("/api/admin/users/$user->id/storage")
            ->assertStatus(200)
            ->assertExactJson([
                "data" => [
                    "id"         => $user->id,
                    "type"       => "storage",
                    "attributes" => [
                        "used"       => "5.00MB",
                        "capacity"   => "5GB",
                        "percentage" => 0.1,
                    ],
                    "meta"       => [
                        "images"    => [
                            "used"       => '1.00MB',
                            "percentage" => 0.02,
                        ],
                        "audios"    => [
                            "used"       => '1.00MB',
                            "percentage" => 0.02,
                        ],
                        "videos"    => [
                            "used"       => '1.00MB',
                            "percentage" => 0.02,
                        ],
                        "documents" => [
                            "used"       => '1.00MB',
                            "percentage" => 0.02,
                        ],
                        "others"    => [
                            "used"       => '1.00MB',
                            "percentage" => 0.02,
                        ]
                    ]
                ]
            ]);
    }
}
