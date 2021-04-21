<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Client;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
}
