<?php

namespace Tests\Feature\Accounts;

use App\Models\File;
use App\Models\Folder;
use App\Models\User;
use Tests\TestCase;

class PersonalAccessTokenTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_user_token()
    {
        $user = User::factory(User::class)
            ->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/user/token/create', [
                'name' => 'token',
            ])
            ->assertStatus(201);

        dd(json_decode($response->content(), true));

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name'         => 'token',
        ]);
    }

    /**
     * @test
     */
    public function it_revoke_user_token()
    {
        $user = User::factory(User::class)
            ->create();

        $user->createToken('token');

        $token_id = $user->tokens()->first()->id;

        $this
            ->actingAs($user)
            ->deleteJson("/api/user/token/revoke/$token_id")
            ->assertStatus(204);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token_id,
        ]);
    }

    /**
     * @test
     */
    public function it_get_user_tokens()
    {
        $user = User::factory(User::class)
            ->create();

        $user->createToken('token');

        $token = $user->tokens()->first();

        $this
            ->actingAs($user)
            ->getJson('/api/user/tokens')
            ->assertStatus(200)
            ->assertJsonFragment([
                'id'             => $token->id,
                'tokenable_type' => $token->tokenable_type,
                'tokenable_id'   => $user->id,
                'name'           => $token->name,
                'abilities'      => $token->abilities,
            ]);
    }

    /**
     * @test
     */
    public function it_use_user_token_in_public_api_request()
    {
        $user = User::factory(User::class)
            ->create();

        $folder = Folder::factory(Folder::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $file = File::factory(File::class)
            ->create([
                'user_id'   => $user->id,
                'folder_id' => $folder->id,
            ]);

        $token = $user->createToken('token')->plainTextToken;

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);

        $this->assertDatabaseHas('folders', [
            'id'      => $folder->id,
            'user_id' => $user->id,
        ]);

        $this
            ->withToken($token)
            ->getJson("/api/browse/folders/$folder->id")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $file->id,
            ]);
    }
}
