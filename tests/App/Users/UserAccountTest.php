<?php
namespace Tests\Feature\Accounts;

use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
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

        Storage::disk('local')
            ->assertExists($user->settings->getRawOriginal('avatar'));
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
                    'id'            => (string) $user->id,
                    'type'          => 'user',
                    'attributes'    => [
                        'storage_capacity'          => '5',
                        'subscription'              => false,
                        'incomplete_payment'        => null,
                        'stripe_customer'           => false,
                        'email'                     => $user->email,
                        'role'                      => $user->role,
                        'two_factor_authentication' => false,
                        'folders'                   => [],
                        'storage'                   => [
                            'used'               => 0,
                            'used_formatted'     => '0.00%',
                            'capacity'           => '5',
                            'capacity_formatted' => '5GB',
                        ],
                        'created_at_formatted' => format_date($user->created_at, '%d. %B. %Y'),
                        'created_at'           => $user->created_at->toJson(),
                        'updated_at'           => $user->updated_at->toJson(),
                    ],
                    'relationships' => [
                        'settings'   => [
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
                                    'phone_number' => $user->settings->phone_number,
                                    'timezone'     => $user->settings->timezone,
                                ],
                            ],
                        ],
                        'favourites' => [
                            'data' => [
                                'id'         => (string) $user->id,
                                'type'       => 'favourite_folders',
                                'attributes' => [
                                    'folders' => [],
                                ],
                            ],
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
