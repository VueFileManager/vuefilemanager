<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\ResetPassword;
use DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Notification;
use Laravel\Sanctum\Sanctum;
use Storage;
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

    /**
     * @test
     */
    public function it_send_reset_password_for_user()
    {
        Notification::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->postJson("/api/admin/users/$user->id/reset-password")
            ->assertStatus(204);

        Notification::assertTimesSent(1, ResetPassword::class);
    }

    /**
     * @test
     */
    public function it_change_user_storage_capacity()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->patchJson("/api/admin/users/$user->id/capacity", [
            'attributes' => [
                'storage_capacity' => 10
            ]
        ])->assertStatus(200);

        $this->assertDatabaseHas('user_settings', [
            'user_id'          => $user->id,
            'storage_capacity' => 10,
        ]);
    }

    /**
     * @test
     */
    public function it_change_user_role()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->patchJson("/api/admin/users/$user->id/role", [
            'attributes' => [
                'role' => 'admin'
            ]
        ])->assertStatus(200);

        $this->assertTrue(User::find($user->id)->role === 'admin');
    }

    /**
     * @test
     */
    public function it_create_new_user_with_avatar()
    {
        Storage::fake('local');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->postJson("/api/admin/users/create", [
            'name'                  => 'John Doe',
            'role'                  => 'user',
            'email'                 => 'john@doe.com',
            'password'              => 'VerySecretPassword',
            'storage_capacity'      => 15,
            'password_confirmation' => 'VerySecretPassword',
            'avatar'                => $avatar,
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com'
        ]);

        $this->assertDatabaseHas('user_settings', [
            'name' => 'John Doe'
        ]);

        Storage::disk('local')
            ->assertExists(User::whereEmail('john@doe.com')->first()->settings->getRawOriginal('avatar'));
    }
}
