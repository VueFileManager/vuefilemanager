<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SetupService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Storage;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use DatabaseMigrations;

    public function __construct()
    {
        parent::__construct();
        $this->setup = app()->make(SetupService::class);
    }

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

    /**
     * @test
     */
    public function it_update_user_avatar()
    {
        Storage::fake('local');

        $this->setup->create_directories();

        $user = User::factory(User::class)
            ->create();

        Sanctum::actingAs($user);

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->patchJson('/api/user/relationships/settings', [
            'avatar'  => $avatar,
        ])->assertStatus(204);

        Storage::disk('local')
            ->assertExists($user->settings->avatar);
    }
}
