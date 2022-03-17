<?php
namespace Tests\Domain\Admin;

use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Laravel\Sanctum\Sanctum;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use App\Users\Notifications\ResetPassword;

class AdminTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_server_status()
    {
        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson('/api/admin/status')
            ->assertOk();
    }

    /**
     * @test
     */
    public function it_get_all_users()
    {
        $users = User::factory()
            ->hasSettings()
            ->count(5)
            ->create(['role' => 'user']);

        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $users->each(
            fn ($user) =>
            $this
                ->actingAs($admin)
                ->getJson('/api/admin/users?page=1')
                ->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user->id,
                ])
        );
    }

    /**
     * @test
     */
    public function it_get_single_user()
    {
        $user = User::factory()
            ->hasSettings()
            ->create(['role' => 'user']);

        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson("/api/admin/users/$user->id")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $user->id,
            ]);
    }

    /**
     * todo: complete test
     */
    public function it_get_non_existed_user_subscription()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson("/api/admin/users/$user->id/subscription")
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_get_user_storage_detail()
    {
        $user = User::factory()
            ->create(['role' => 'user']);

        collect(['image', 'audio', 'video', 'pdf', 'zip'])
            ->each(function ($mimetype) use ($user) {
                File::factory()
                    ->create([
                        'user_id'  => $user->id,
                        'type'     => $mimetype,
                        'mimetype' => $mimetype,
                        'filesize' => 1000000,
                    ]);
            });

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->getJson("/api/admin/users/$user->id/storage")
            ->assertStatus(200)
            ->assertJsonFragment([
                'used'       => '5.00MB',
                'capacity'   => '1GB',
                'percentage' => 0.5,
            ]);
    }

    /**
     * @test
     */
    public function it_send_reset_password_for_user()
    {
        $user = User::factory()
            ->create(['role' => 'user']);

        $admin = User::factory()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->postJson("/api/admin/users/$user->id/reset-password")
            ->assertStatus(204);

        Notification::assertTimesSent(1, ResetPassword::class);
    }

    /**
     * @test
     */
    public function it_change_user_storage_capacity()
    {
        $user = User::factory()
            ->hasSettings()
            ->create(['role' => 'user']);

        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson("/api/admin/users/$user->id/capacity", [
                'attributes' => [
                    'max_storage_amount' => 10,
                ],
            ])->assertStatus(200);

        $this->assertDatabaseHas('user_settings', [
            'user_id'            => $user->id,
        ])->assertDatabaseHas('user_limitations', [
            'max_storage_amount' => 10,
        ]);
    }

    /**
     * @test
     */
    public function it_change_user_role()
    {
        $user = User::factory()
            ->hasSettings()
            ->create(['role' => 'user']);

        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $this
            ->actingAs($admin)
            ->patchJson("/api/admin/users/$user->id/role", [
                'attributes' => [
                    'role' => 'admin',
                ],
            ])->assertStatus(200);

        $this->assertTrue(User::find($user->id)->role === 'admin');
    }

    /**
     * @test
     */
    public function it_create_new_user_with_avatar()
    {
        $admin = User::factory()
            ->hasSettings()
            ->create(['role' => 'admin']);

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this
            ->actingAs($admin)
            ->postJson('/api/admin/users', [
                'name'                    => 'John Doe',
                'role'                    => 'user',
                'email'                   => 'john@doe.com',
                'password'                => 'VerySecretPassword',
                'max_storage_amount'      => 15,
                'password_confirmation'   => 'VerySecretPassword',
                'avatar'                  => $avatar,
            ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com',
        ]);

        $this->assertNotNull(User::whereEmail('john@doe.com')
            ->get('email_verified_at'));

        $this->assertDatabaseHas('user_settings', [
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);

        $avatar = User::whereEmail('john@doe.com')->first()->settings->getRawOriginal('avatar');

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn ($size) =>
                Storage::disk('local')
                    ->assertExists("avatars/{$size['name']}-{$avatar}")
            );
    }

    /**
     * @test
     */
    public function it_delete_user_with_all_data()
    {
        // Create and login user
        $user = User::factory()
            ->hasSettings()
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        // Create folders
        $folders = Folder::factory()
            ->count(2)
            ->create(['user_id' => $user->id]);

        // Create favourite folders
        $folders->each(function ($folder) use ($user) {
            $user->favouriteFolders()->attach($folder->id);
        });

        // Create shares
        Share::factory()
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
                    'parent_id' => null,
                    'path'      => "/$file->name",
                    'is_last'   => 'true',
                ])->assertStatus(201);
            });

        $file_ids = File::all()
            ->pluck('id');

        // Upload avatar
        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->patchJson('/api/user/settings', [
            'avatar' => $avatar,
        ])->assertStatus(204);

        $user = User::whereRole('user')
            ->first();

        // Create and login admin
        $admin = User::factory()
            ->create(['role' => 'admin']);

        Sanctum::actingAs($admin);

        // Delete user
        $this->deleteJson("/api/admin/users/$user->id/delete", [
            'name' => $user->settings->name,
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
}
