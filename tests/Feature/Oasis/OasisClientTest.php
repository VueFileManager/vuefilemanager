<?php

namespace Tests\Feature\Oasis;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Oasis\Client;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;

class OasisClientTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_test_client_factory()
    {
        $client = Client::factory(Client::class)
            ->create();

        $this->assertDatabaseHas('clients', [
            'name' => $client->name,
        ]);
    }

    /**
     * @test
     */
    public function it_get_all_clients()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $this->getJson('/api/oasis/clients')
            ->assertJsonFragment([
                'id' => $client->id,
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_search_user_client()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create([
                'user_id' => $user->id,
                'name' => 'VueFileManager Inc.',
                'email' => 'info@company.com',
            ]);

        $this->getJson('/api/oasis/clients/search?query=vue')
            ->assertJsonFragment([
                'id' => $client->id,
            ])->assertStatus(200);

        $this->getJson('/api/oasis/clients/search?query=info')
            ->assertJsonFragment([
                'id' => $client->id,
            ])->assertStatus(200);
    }
}
