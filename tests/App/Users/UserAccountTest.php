<?php
namespace Tests\App\Users;

use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class UserAccountTest extends TestCase
{
    /**
     * @test
     */
    public function it_generate_and_store_user()
    {
        $user = User::factory()
            ->hasSettings()
            ->create(['role' => 'user']);

        $this->assertDatabaseHas('users', [
            'id'   => $user->id,
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->id,
        ]);

        Storage::disk('local')
            ->assertExists('files/' . User::first()->id);
    }

    /**
     * @test
     */
    public function it_test_user_timezone()
    {
        $user = User::factory()
            ->hasSettings([
                'timezone' => '2.0',
            ])
            ->create();

        Folder::factory()
            ->create([
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $this
            ->actingAs($user)
            ->getJson('/api/browse/folders/undefined')
            ->assertJsonFragment([
                'created_at' => '01. Jan. 2021, 02:00',
            ]);
    }

    /**
     * @test
     */
    public function it_change_user_password_in_profile_settings()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/user/password', [
                'current'               => 'secret',
                'password'              => 'VerySecretPassword',
                'password_confirmation' => 'VerySecretPassword',
            ])->assertStatus(200);

        $this
            ->actingAs($user)
            ->postJson('/logout')
            ->assertStatus(204);

        $this->postJson('/login', [
            'email'    => $user->email,
            'password' => 'VerySecretPassword',
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_update_user_settings()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->patchJson('/api/user/settings', [
                'name'  => 'address',
                'value' => 'Jantar',
            ])->assertStatus(200);

        $this->assertDatabaseHas('user_settings', [
            'address' => 'Jantar',
        ]);
    }

    /**
     * @test
     */
    public function it_update_user_avatar()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this
            ->actingAs($user)
            ->postJson('/api/user/avatar', [
                'avatar' => $avatar,
            ])
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'The avatar was successfully updated.',
            ]);

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn ($size) => Storage::disk('local')
                    ->assertExists("avatars/{$size['name']}-{$user->settings->getRawOriginal('avatar')}")
            );
    }

    /**
     * @test
     */
    public function it_get_user_data()
    {
        $user = User::factory()
            ->hasSettings()
            ->create();

        $this
            ->actingAs($user)
            ->getJson('/api/user')
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id'            => (string) $user->id,
                    'type'          => 'user',
                    'attributes'    => [
                        'avatar'                    => null,
                        'color'                     => $user->settings->color,
                        'email'                     => $user->email,
                        'role'                      => $user->role,
                        'socialite_account'         => false,
                        'two_factor_authentication' => false,
                        'two_factor_confirmed_at'   => null,
                        'storage'                   => [
                            'used'               => 0,
                            'used_formatted'     => '0%',
                            'capacity'           => '1',
                            'capacity_formatted' => '1GB',
                        ],
                        'created_at'                => format_date($user->created_at, 'd. M. Y'),
                        'updated_at'                => format_date($user->updated_at, 'd. M. Y'),
                    ],
                    'meta'          => [
                        'restrictions' => [
                            'canCreateFolder'      => true,
                            'canCreateTeamFolder'  => true,
                            'canDownload'          => true,
                            'canInviteTeamMembers' => true,
                            'canUpload'            => true,
                            'reason' => null,
                        ],
                    ],
                    'relationships' => [
                        'creditCards' => [
                            'data' => [],
                        ],
                        'settings'    => [
                            'data' => [
                                'id'         => (string) $user->id,
                                'type'       => 'settings',
                                'attributes' => [
                                    'avatar'       => $user->settings->avatar,
                                    'name'         => $user->settings->name,
                                    'address'      => $user->settings->address,
                                    'state'        => $user->settings->state,
                                    'city'         => $user->settings->city,
                                    'postal_code'  => $user->settings->postal_code,
                                    'country'      => $user->settings->country,
                                    'first_name'   => $user->settings->first_name,
                                    'last_name'    => $user->settings->last_name,
                                    'phone_number' => $user->settings->phone_number,
                                    'timezone'     => $user->settings->timezone,
                                ],
                            ],
                        ],
                        'favourites'         => [],
                        'readNotifications'  => [
                            'data' => [],
                        ],
                        'unreadNotifications'  => [
                            'data' => [],
                        ],
                    ],
                ],
            ]);
    }

    /**
     * @test
     */
    public function it_verify_user_email()
    {
        $user = User::factory()
            ->create([
                'email_verified_at' => null,
            ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this
            ->getJson($verificationUrl)
            ->assertRedirect('successfully-verified');

        $this->assertNotNull(User::find($user->id)->email_verified_at);
    }

    /**
     * @test
     */
    public function it_resend_user_verify_email()
    {
        $user = User::factory()
            ->create([
                'email_verified_at' => null,
            ]);

        $this->postJson('/api/user/verify', [
            'email' => $user->email,
        ])
            ->assertStatus(200);

        Notification::assertTimesSent(1, VerifyEmail::class);
    }
}
