<?php

namespace Tests\Feature\Admin;

use App\Models\File;
use App\Models\Folder;
use App\Models\Setting;
use App\Models\Share;
use App\Models\User;
use App\Models\Zip;
use App\Notifications\ResetPassword;
use App\Services\SetupService;
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

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

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
            ->assertExists(
                User::whereEmail('john@doe.com')->first()->settings->getRawOriginal('avatar')
            );
    }

    /**
     * @test
     */
    public function it_delete_user_with_all_data()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        // Create and login user
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        // Create folders
        $folders = Folder::factory(Folder::class)
            ->count(2)
            ->create(['user_id' => $user->id]);

        // Create favourite folders
        $folders->each(function ($folder) use ($user) {
            $user->favouriteFolders()->attach($folder->id);
        });

        // Create zips
        Zip::factory(Zip::class)
            ->count(2)
            ->create(['user_id' => $user->id]);

        // Create shares
        Share::factory(Share::class)
            ->count(2)
            ->create(['user_id' => $user->id]);

        // Upload files
        collect([0, 1])
            ->each(function ($index) {

                $file = UploadedFile::fake()
                    ->create("fake-file-$index.pdf", 1200, 'application/pdf');

                $this->postJson('/api/upload', [
                    'filename'  => $file->name,
                    'file'      => $file,
                    'folder_id' => null,
                    'is_last'   => true,
                ])->assertStatus(201);
            });

        $file_ids = File::all()
            ->pluck('id');

        // Upload avatar
        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->patchJson('/api/user/relationships/settings', [
            'avatar' => $avatar,
        ])->assertStatus(204);

        $user = User::whereRole('user')
            ->first();

        // Create and login admin
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        // Delete user
        $this->deleteJson("/api/admin/users/$user->id/delete", [
            'name' => $user->settings->name
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('user_settings', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('folders', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('shares', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('favourite_folder', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('files', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseMissing('zips', [
            'user_id' => $user->id,
        ]);

        $file_ids
            ->each(function ($id, $index) use ($user) {

                Storage::disk('local')
                    ->assertMissing(
                        "files/$user->id/fake-file-$index.pdf"
                    );

                Storage::disk('local')
                    ->assertMissing(
                        "files/fake-file-$index.pdf"
                    );
            });

        Storage::disk('local')
            ->assertMissing($user->settings->getRawOriginal('avatar'));
    }

    /**
     * @test
     */
    public function it_get_all_pages()
    {
        $this->setup->seed_default_pages();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        collect(['terms-of-service', 'privacy-policy', 'cookie-policy'])
            ->each(function ($slug) {
                $this->getJson('/api/admin/pages')
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'slug' => $slug
                    ]);
            });
    }

    /**
     * @test
     */
    public function it_get_page()
    {
        $this->setup->seed_default_pages();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/pages/terms-of-service')
            ->assertStatus(200)
            ->assertJsonFragment([
                'slug' => 'terms-of-service'
            ]);
    }

    /**
     * @test
     */
    public function it_update_page()
    {
        $this->setup->seed_default_pages();

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->patchJson('/api/admin/pages/terms-of-service', [
            'name'  => 'title',
            'value' => 'New Title'
        ])->assertStatus(204);

        $this->assertDatabaseHas('pages', [
            'title' => 'New Title'
        ]);
    }

    /**
     * @test
     */
    public function it_get_settings()
    {
        $this->setup->seed_default_settings('Extended');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/settings?column=section_features|section_feature_boxes')
            ->assertStatus(200)
            ->assertJsonFragment([
                'section_features'      => '1',
                'section_feature_boxes' => '1',
            ]);
    }

    /**
     * @test
     */
    public function it_update_settings()
    {
        $this->setup->seed_default_settings('Extended');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->patchJson('/api/admin/settings', [
            'name'  => 'header_title',
            'value' => 'New Header Title'
        ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'value' => 'New Header Title'
        ]);
    }

    /**
     * @test
     */
    public function it_update_settings_image()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        Setting::forceCreate([
            'name'  => 'app_logo',
            'value' => null,
        ]);

        $logo = UploadedFile::fake()
            ->image('fake-image.jpg');

        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->patchJson('/api/admin/settings', [
            'name'     => 'app_logo',
            'app_logo' => $logo
        ])->assertStatus(204);

        $this->assertDatabaseMissing('settings', [
            'app_logo' => null
        ]);

        Storage::assertExists(
            get_setting('app_logo')
        );
    }

    /**
     * @test
     */
    public function it_flush_cache()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->getJson('/api/admin/settings/flush-cache')
            ->assertStatus(204);
    }

    /**
     * @test
     */
    public function it_set_stripe()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->postJson('/api/admin/settings/stripe', [
            'currency'      => 'EUR',
            'key'           => '123456789',
            'secret'        => '123456789',
            'webhookSecret' => '123456789',
        ])->assertStatus(204);

        $this->assertDatabaseHas('settings', [
            'name'  => 'stripe_currency',
            'value' => 'EUR',
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'payments_configured',
            'value' => 1,
        ]);

        $this->assertDatabaseHas('settings', [
            'name'  => 'payments_active',
            'value' => 1,
        ]);
    }

    /**
     * @test
     */
    public function it_set_email()
    {
        $admin = User::factory(User::class)
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        $this->postJson('/api/admin/settings/email', [
            'driver'     => 'smtp',
            'host'       => 'smtp.email.com',
            'port'       => 25,
            'username'   => 'john@doe.com',
            'password'   => 'secret',
            'encryption' => 'tls',
        ])->assertStatus(204);
    }
}
