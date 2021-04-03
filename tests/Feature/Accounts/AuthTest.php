<?php

namespace Tests\Feature\Accounts;

use App\Models\Setting;
use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\Sanctum;
use Notification;
use Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

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
    public function it_register_user()
    {
        collect([
            [
                'name'  => 'storage_default',
                'value' => 12,
            ],
            [
                'name'  => 'registration',
                'value' => 1,
            ],
        ])->each(function ($setting) {
            Setting::create([
                'name'  => $setting['name'],
                'value' => $setting['value'],
            ]);
        });

        $this->postJson('/register', [
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com',
        ]);

        $this->assertDatabaseHas('user_settings', [
            'name'             => 'John Doe',
            'storage_capacity' => 12,
        ]);

        Storage::disk('local')
            ->assertExists('files/' . User::first()->id);
    }

    /**
     * @test
     */
    public function it_check_if_user_exist_and_return_name_with_avatar()
    {
        $user = User::factory(User::class)
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
        $user = User::factory(User::class)
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
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/logout')
            ->assertStatus(204);
    }

    /**
     * @test
     */
    public function it_send_reset_link_to_email()
    {
        Notification::fake();

        $user = User::factory(User::class)
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
        $user = User::factory(User::class)
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
