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
    public function it_create_user_from_register_form()
    {
        collect([
            [
                'name'  => 'user_verification',
                'value' => 1,
            ],
            [
                'name'  => 'default_max_storage_amount',
                'value' => 10,
            ],
        ])->each(function ($setting) {
            Setting::updateOrCreate([
                'name' => $setting['name'],
            ], [
                'value' => $setting['value'],
            ]);
        });

        $this->postJson('api/register', [
            'role'                  => 'admin',
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(201);

        $this
            ->assertDatabaseHas('users', [
                'email'             => 'john@doe.com',
                'email_verified_at' => null,
                'role'              => 'user',
            ])
            ->assertDatabaseHas('user_settings', [
                'first_name' => 'John',
                'last_name'  => 'Doe',
            ])
            ->assertDatabaseHas('user_limitations', [
                'max_storage_amount' => 10,
            ]);

        Storage::disk('local')
            ->assertExists('files/' . User::first()->id);

        Notification::assertTimesSent(1, VerifyEmail::class);
    }

    /**
     * @test
     */
    public function it_register_user_when_metered_billing_is_active()
    {
        // Seed default settings
        collect([
            [
                'name'  => 'subscription_type',
                'value' => 'metered',
            ],
            [
                'name'  => 'allowed_registration_bonus',
                'value' => 0,
            ],
            [
                'name'  => 'registration_bonus_amount',
                'value' => 15,
            ],
        ])->each(function ($setting) {
            Setting::updateOrCreate([
                'name' => $setting['name'],
            ], [
                'value' => $setting['value'],
            ]);
        });

        // Create metered plan
        $plan = Plan::factory()
            ->create([
                'status'   => 'active',
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        $this->postJson('api/register', [
            'role'                  => 'admin',
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(201);

        $this
            ->assertDatabaseCount('transactions', 0)
            ->assertDatabaseHas('users', [
                'role'  => 'user',
                'email' => 'john@doe.com',
            ])
            ->assertDatabaseHas('subscriptions', [
                'status'    => 'active',
                'name'      => $plan->name,
                'ends_at'   => null,
                'renews_at' => now()->addDays(config('subscription.metered_billing.settlement_period')),
            ])
            ->assertDatabaseHas('balances', [
                'currency' => 'USD',
                'amount'   => 0,
            ])
            ->assertDatabaseHas('user_settings', [
                'first_name' => 'John',
                'last_name'  => 'Doe',
            ]);
    }

    /**
     * @test
     */
    public function it_register_user_when_metered_billing_is_active_with_registration_bonus()
    {
        // Seed default settings
        collect([
            [
                'name'  => 'subscription_type',
                'value' => 'metered',
            ],
            [
                'name'  => 'allowed_registration_bonus',
                'value' => 1,
            ],
            [
                'name'  => 'registration_bonus_amount',
                'value' => 15,
            ],
        ])->each(function ($setting) {
            Setting::updateOrCreate([
                'name' => $setting['name'],
            ], [
                'value' => $setting['value'],
            ]);
        });

        // Create metered plan
        $plan = Plan::factory()
            ->create([
                'status'   => 'active',
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        $this->postJson('api/register', [
            'role'                  => 'admin',
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(201);

        $user = User::first();

        $this
            ->assertDatabaseHas('users', [
                'role'  => 'user',
                'email' => 'john@doe.com',
            ])
            ->assertDatabaseHas('subscriptions', [
                'status'    => 'active',
                'name'      => $plan->name,
                'ends_at'   => null,
                'renews_at' => now()->addDays(config('subscription.metered_billing.settlement_period')),
            ])
            ->assertDatabaseHas('balances', [
                'currency' => 'USD',
                'amount'   => 15,
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'  => $user->id,
                'amount'   => 15.00,
                'currency' => 'USD',
                'status'   => 'completed',
                'type'     => 'credit',
                'driver'   => 'system',
            ])
            ->assertDatabaseHas('user_settings', [
                'first_name' => 'John',
                'last_name'  => 'Doe',
            ]);

        Notification::assertSentTo($user, RegistrationBonusAddedNotification::class);
    }

    /**
     * @test
     */
    public function it_try_register_when_registration_is_disabled()
    {
        Setting::updateOrCreate([
            'name' => 'registration',
        ], [
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

        $this->postJson('/api/password/recover', [
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

    /**
     * @test
     */
    public function it_create_user_from_register_form_with_reCaptcha()
    {
        Setting::updateOrCreate([
            'name' => 'allowed_recaptcha',
        ], [
            'value' => 1,
        ]);

        $this->mock(ReCaptchaRules::class, function ($mock) {
            $mock->shouldReceive('passes')->andReturn(true);
        });

        $this->postJson('api/register', [
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
            'reCaptcha'             => 'fakeToken',
        ])->assertStatus(201);

        $this
            ->assertDatabaseHas('users', [
                'email' => 'john@doe.com',
            ]);
    }
}
