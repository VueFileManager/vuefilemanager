<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Invoice;
use App\Models\Oasis\InvoiceProfile;
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
    public function it_create_new_client_with_avatar()
    {
        Storage::fake('local');

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/clients', [
            'avatar' => $avatar,
            'name'   => 'VueFileManager Inc.',

            'email'        => 'howdy@hi5ve.digital',
            'phone_number' => '+421 950 123 456',

            'address'     => 'Does 12',
            'city'        => 'Bratislava',
            'postal_code' => '076 54',
            'country'     => 'SK',

            'ico'    => '11111111',
            'dic'    => '11111111',
            'ic_dph' => 'SK11111111',
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
    public function it_create_new_client_without_avatar_and_contact_info()
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
    public function it_update_client_info()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create(['user_id' => $user->id]);

        $this->patchJson("/api/oasis/clients/$client->id", [
            'name'  => 'name',
            'value' => 'VueFileManager Inc.',
        ])->assertStatus(204);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name'    => 'VueFileManager Inc.',
        ]);
    }

    /**
     * @test
     */
    public function it_update_client_avatar()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create(['user_id' => $user->id]);

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $this->patchJson("/api/oasis/clients/$client->id", [
            'name'   => 'avatar',
            'avatar' => $avatar,
        ])->assertStatus(204);

        $this->assertDatabaseMissing('clients', [
            'user_id' => $user->id,
            'avatar'  => null,
        ]);

        Storage::disk('local')->assertExists(
            Client::first()->getRawOriginal('avatar')
        );
    }

    /**
     * @test
     */
    public function it_delete_client()
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

        $this->assertDatabaseMissing('clients', [
            'id' => $client->id
        ]);

        Storage::disk('local')
            ->assertMissing('avatar/fake-image.jpg');
    }

    /**
     * @test
     */
    public function it_get_single_client()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $this->getJson("/api/oasis/clients/$client->id")
            ->assertJsonFragment([
                'id' => $client->id,
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_client_client_invoices()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create([
                'user_id' => $user->id,
            ]);

        $profile = InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'        => $user->id,
                'client_id'      => $client->id,
                'invoice_type'   => 'regular-invoice',
                'invoice_number' => 2001212,
                'client'         => [
                    'name' => 'VueFileManager Inc.',
                ],
                'user'           => $profile->toArray(),
            ]);

        $this->getJson("/api/oasis/clients/$client->id/invoices")
            ->assertJsonFragment([
                'id' => $invoice->id,
            ])
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_all_clients()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        Client::factory(Client::class)
            ->count(2)
            ->create([
                'user_id' => $user->id,
            ]);

        $this->getJson('/api/oasis/clients')
            ->assertStatus(200);
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
