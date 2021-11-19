<?php
namespace Tests\App\Users;

use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use Domain\Settings\Models\Setting;
use Illuminate\Support\Facades\Password;
use App\Users\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;

class SignFlowTest extends TestCase
{
    /**
     * @test
     */
    public function it_register_user()
    {
        collect([
            [
                'name'  => 'default_storage_amount',
                'value' => 12,
            ],
            [
                'name'  => 'registration',
                'value' => 1,
            ],
            [
                'name'  => 'user_verification',
                'value' => 1,
            ],
        ])->each(function ($setting) {
            Setting::create([
                'name'  => $setting['name'],
                'value' => $setting['value'],
            ]);
        });

        $this->postJson('api/register', [
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email'             => 'john@doe.com',
            'email_verified_at' => null,
        ])->assertDatabaseHas('user_settings', [
            'name' => 'John Doe',
        ])->assertDatabaseHas('user_limitations', [
            'max_storage_amount' => 12,
        ]);

        Storage::disk('local')
            ->assertExists('files/' . User::first()->id);

        Notification::assertTimesSent(1, VerifyEmail::class);
    }

    /**
     * @test
     */
    public function it_try_register_when_registration_is_disabled()
    {
        Setting::create([
            'name'  => 'registration',
            'value' => 0,
        ]);

        $this->postJson('api/register', [
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(401);

        $this->assertDatabaseMissing('users', [
            'email'             => 'john@doe.com',
            'email_verified_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_try_register_from_disabled_email_provider()
    {
        $this->postJson('api/register', [
            'email'                 => 'john@maildrop.cc',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(422);

        $this->assertDatabaseMissing('users', [
            'email'             => 'john@doe.com',
            'email_verified_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function it_check_if_user_exist_and_return_name_with_avatar()
    {
        $user = User::factory()
            ->create(['email' => 'john@doe.com']);

        $this->postJson('/api/user/check', [
            'email' => $user->email,
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_check_non_existed_user_and_return_not_found()
    {
        $this->postJson('/api/user/check', [
            'email' => 'jane@doe.com',
        ])->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_login_user()
    {
        $user = User::factory()
            ->create(['email' => 'john@doe.com']);

        $this->postJson('/login', [
            'email'    => $user->email,
            'password' => 'secret',
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_logout_user()
    {
        $user = User::factory()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/logout')
            ->assertStatus(204);
    }

    /**
     * @test
     */
    public function it_send_reset_link_to_email()
    {
        $user = User::factory()
            ->create(['email' => 'john@doe.com']);

        $this->postJson('/api/password/email', [
            'email' => $user->email,
        ])->assertStatus(200);

        Notification::assertTimesSent(1, ResetPassword::class);
    }

    /**
     * @test
     */
    public function it_reset_user_password()
    {
        $user = User::factory()
            ->create(['email' => 'john@doe.com']);

        // Get password token
        $token = Password::getRepository()
            ->create($user);

        $this->postJson('/api/password/reset', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'VeryStrongPassword',
            'password_confirmation' => 'VeryStrongPassword',
        ])->assertStatus(200);

        $this->assertDatabaseMissing('password_resets', [
            'email' => $user->email,
        ]);
    }
}
