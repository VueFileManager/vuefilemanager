<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class UserTest extends TestCase
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
        $this->postJson('/register', [
            'email'                 => 'john@doe.com',
            'password'              => 'SecretPassword',
            'password_confirmation' => 'SecretPassword',
            'name'                  => 'John Doe',
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email'  => 'john@doe.com',
        ]);

        $this->assertDatabaseHas('user_settings', [
            'name'    => 'John Doe',
        ]);

        Storage::disk('local')
            ->assertExists('files/' . User::first()->id);
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
    public function it_update_user_settings()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->patchJson('/api/user/relationships/settings', [
            'name'  => 'address',
            'value' => 'Jantar',
        ])->assertStatus(204);

        $this->assertDatabaseHas('user_settings', [
            'address' => 'Jantar',
        ]);
    }
}
