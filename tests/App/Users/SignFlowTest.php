<?php
namespace Tests\App\Users;

use Storage;
use Notification;
use Tests\TestCase;
use App\Users\Models\User;
use App\Users\Rules\ReCaptchaRules;
use Domain\Settings\Models\Setting;
use Illuminate\Support\Facades\Password;
use App\Users\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use App\Users\Notifications\RegistrationBonusAddedNotification;

class SignFlowTest extends TestCase
{
    /**
     * @test
     */
    public function it_check_if_user_exist_and_return_name_with_avatar()
    {
        $user = User::factory()
            ->hasSettings()
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
            ->create();

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
            ->hasSettings()
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
