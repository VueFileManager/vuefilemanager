<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_change_user_password_in_profile_settings()
    {
        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/user/password', [
            'current_password'      => 'secret',
            'password'              => 'VerySecretPassword',
            'password_confirmation' => 'VerySecretPassword',
        ])->assertStatus(204);

        // TODO: login s novym heslom
    }
}
