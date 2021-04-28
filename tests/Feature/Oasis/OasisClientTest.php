<?php

namespace Tests\Feature\Oasis;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Oasis\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
    public function user_create_new_client_with_avatar()
    {
        Storage::fake('local');

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/clients', [
            'avatar'       => $avatar,
            'name'         => 'VueFileManager Inc.',
            'email'        => 'howdy@hi5ve.digital',
            'phone_number' => '+421 950 123 456',
            'address'      => 'Does 12',
            'city'         => 'Bratislava',
            'postal_code'  => '076 54',
            'country'      => 'SK',
            'ico'          => '11111111',
            'dic'          => '11111111',
            'ic_dph'       => 'SK11111111',
        ])->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name'    => 'VueFileManager Inc.',
            'email'   => 'howdy@hi5ve.digital',
        ]);

        Storage::disk('local')
            ->assertExists(
                Client::first()->getRawOriginal('avatar')
            );
    }

    /**
     * @test
     */
    public function user_create_new_client_without_avatar_and_contact_info()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/clients', [
            'avatar'       => null,
            'name'         => 'VueFileManager Inc.',
            'email'        => null,
            'phone_number' => null,
            'address'      => 'Does 12',
            'city'         => 'Bratislava',
            'postal_code'  => '076 54',
            'country'      => 'SK',
            'ico'          => '11111111',
            'dic'          => '11111111',
            'ic_dph'       => 'SK11111111',
        ])->assertStatus(201);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name'    => 'VueFileManager Inc.',
        ]);
    }

    /**
     * @test
     */
    public function user_delete_client()
    {
        Storage::fake('local');

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        Storage::putFileAs('avatar', $avatar, 'fake-image.jpg');

        $client = Client::factory(Client::class)
            ->create([
                'avatar'  => 'avatar/fake-image.jpg',
                'user_id' => $user->id,
            ]);

        $this->deleteJson("/api/oasis/clients/$client->id")
            ->assertStatus(204);

        Storage::disk('local')
            ->assertMissing('avatar/fake-image.jpg');
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
                'name'    => 'VueFileManager Inc.',
                'email'   => 'info@company.com',
            ]);

        $this->getJson('/api/oasis/clients/search?query=vue')
            ->assertJsonFragment([
                'id' => $client->id,
            ])->assertStatus(200);

        $this->getJson('/api/oasis/clients/search?query=inf')
            ->assertJsonFragment([
                'id' => $client->id,
            ])->assertStatus(200);
    }
}
