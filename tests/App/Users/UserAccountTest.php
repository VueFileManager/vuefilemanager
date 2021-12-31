<?php

namespace Tests\Feature\Accounts;

use DB;
use Domain\Settings\Models\Setting;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Two\FacebookProvider;
use Mockery;
use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Folders\Models\Folder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Laravel\Socialite\Contracts\Factory as Socialite;

class UserAccountTest extends TestCase
{
    /**
     * @test
     */
    public function it_generate_and_store_user()
    {
        $user = User::factory(User::class)
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
    public function it_socialite_redirect()
    {
        $response = $this->get('api/socialite/google/redirect');

        $this->assertStringContainsString('accounts.google.com/o/oauth2/auth', $response['url']);
    }

    /**
     * @test
     */
    public function it_socialite_callback()
    {
        // Set default settings
        DB::table('settings')->insert([
            [
                'name'  => 'registration',
                'value' => 1,
            ], [
                'name'  => 'storage_default',
                'value' => 5,
            ]
        ]);

        // Create fake image
        $fakeImage = UploadedFile::fake()
            ->image('fake-avatar.jpg');

        Http::fake([
            'https://vuefilemanager.com/avatar.jpg' => Http::response($fakeImage->getContent()),
        ]);

        // Create fake user
        $socialiteUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $socialiteUser->token = 'fake_token';
        $socialiteUser->id = 'fake_id';
        $socialiteUser->name = 'Jane Doe';
        $socialiteUser->email = 'howdy@hi5ve.digital';
        $socialiteUser->avatar = 'https://vuefilemanager.com/avatar.jpg';

        // Mock user with FB provider
        $provider = $this->createMock(FacebookProvider::class);
        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        // Mock socialite
        $stub = $this->createMock(Socialite::class);

        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with mock
        $this->app->instance(Socialite::class, $stub);

        $this->getJson('/api/socialite/facebook/callback');

        $this
            ->assertDatabaseHas('users', [
                'email'          => 'howdy@hi5ve.digital',
                'oauth_provider' => 'facebook',
                'password'       => null,
            ])
            ->assertDatabaseHas('user_settings', [
                'name' => 'Jane Doe',
            ]);

        $user = User::first();

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn($size) => Storage::disk('local')
                    ->assertExists("avatars/{$size['name']}-{$user->settings->getRawOriginal('avatar')}")
            );
    }

    /**
     * todo: finish test
     */
    public function it_test_user_timezone()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Folder::factory(Folder::class)
            ->create([
                'user_id'    => $user->id,
                'created_at' => now(),
            ]);

        $user->settings()->update([
            'timezone' => '2.0',
        ]);

        $this
            ->actingAs($user)
            ->getJson('/api/browse/folders/undefined')
            ->assertJsonFragment([
                'created_at' => '01. January. 2021 at 02:00',
            ]);
    }

    /**
     * @test
     */
    public function it_change_user_password_in_profile_settings()
    {
        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/user/password', [
                'current_password'      => 'secret',
                'password'              => 'VerySecretPassword',
                'password_confirmation' => 'VerySecretPassword',
            ])->assertStatus(204);

        // TODO: login s novym heslom
    }

    /**
     * @test
     */
    public function it_update_user_settings()
    {
        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->patchJson('/api/user/settings', [
                'name'  => 'address',
                'value' => 'Jantar',
            ])->assertStatus(204);

        $this->assertDatabaseHas('user_settings', [
            'address' => 'Jantar',
        ]);
    }

    /**
     * @test
     */
    public function it_update_user_avatar()
    {
        $user = User::factory(User::class)
            ->create();

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this
            ->actingAs($user)
            ->patchJson('/api/user/settings', [
                'avatar' => $avatar,
            ])->assertStatus(204);

        collect(config('vuefilemanager.avatar_sizes'))
            ->each(
                fn($size) => Storage::disk('local')
                    ->assertExists("avatars/{$size['name']}-{$user->settings->getRawOriginal('avatar')}")
            );
    }

    /**
     * @test
     */
    public function it_get_user_data()
    {
        $user = User::factory(User::class)
            ->create();

        $this
            ->actingAs($user)
            ->getJson('/api/user')
            ->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id'            => (string)$user->id,
                    'type'          => 'user',
                    'attributes'    => [
                        'storage_capacity'          => '5',
                        'subscription'              => false,
                        'incomplete_payment'        => null,
                        'stripe_customer'           => false,
                        'email'                     => $user->email,
                        'role'                      => $user->role,
                        'two_factor_authentication' => false,
                        'socialite_account'         => false,
                        'folders'                   => [],
                        'storage'                   => [
                            'used'               => 0,
                            'used_formatted'     => '0.00%',
                            'capacity'           => '5',
                            'capacity_formatted' => '5GB',
                        ],
                        'created_at_formatted'      => format_date($user->created_at, '%d. %B. %Y'),
                        'created_at'                => $user->created_at->toJson(),
                        'updated_at'                => $user->updated_at->toJson(),
                    ],
                    'relationships' => [
                        'settings'   => [
                            'data' => [
                                'id'         => (string)$user->id,
                                'type'       => 'settings',
                                'attributes' => [
                                    'avatar'       => $user->settings->avatar,
                                    'name'         => $user->settings->name,
                                    'address'      => $user->settings->address,
                                    'state'        => $user->settings->state,
                                    'city'         => $user->settings->city,
                                    'postal_code'  => $user->settings->postal_code,
                                    'country'      => $user->settings->country,
                                    'phone_number' => $user->settings->phone_number,
                                    'timezone'     => $user->settings->timezone,
                                ],
                            ],
                        ],
                        'favourites' => [
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
        $user = User::factory(User::class)
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

        $this->assertNotNull(User::find($user->id)->get('email_verified_at'));
    }

    /**
     * @test
     */
    public function it_resend_user_verify_email()
    {
        $user = User::factory(User::class)
            ->create([
                'email_verified_at' => null,
            ]);

        $this->postJson('/api/user/email/verify/resend', [
            'email' => $user->email,
        ])
            ->assertStatus(204);

        Notification::assertTimesSent(1, VerifyEmail::class);
    }
}
