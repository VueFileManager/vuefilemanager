<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
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
                'id'      => $client->id,
                'user_id' => $user->id,
            ])->assertStatus(200);
    }
}
