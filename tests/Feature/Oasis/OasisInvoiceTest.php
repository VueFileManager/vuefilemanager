<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Client;
use App\Models\Oasis\InvoiceProfile;
use App\Notifications\Oasis\InvoiceDeliveryNotification;
use PDF;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Oasis\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;

class OasisInvoiceTest extends TestCase
{
    use DatabaseMigrations, Queueable;

    public function __construct()
    {
        parent::__construct();

        $this->items = [
            [
                'description' => "No, I've made up my mind about it; if I'm Mabel, I'll stay.",
                'amount'      => 1,
                'tax_rate'    => 20,
                'price'       => 200,
            ],
            [
                'description' => "I only knew the right words,' said poor Alice, who felt.",
                'amount'      => 3,
                'tax_rate'    => 20,
                'price'       => 500,
            ],
        ];

        $this->client = [
            'email'       => 'howdy@hi5ve.digital',
            'name'        => 'VueFileManager Inc.',
            'address'     => 'Does 12',
            'city'        => 'Bratislava',
            'postal_code' => '076 54',
            'country'     => 'SK',
            'ico'         => 11111111,
            'dic'         => 11111111,
            'ic_dph'      => 'SK11111111',
        ];
    }

    /**
     * @test
     */
    public function it_test_invoice_item_only_tax_price_function()
    {
        $item = [
            'description' => 'Test 1',
            'amount'      => 1,
            'tax_rate'    => 20,
            'price'       => 20,
        ];

        $this->assertEquals(4, invoice_item_only_tax_price($item));
    }

    /**
     * @test
     */
    public function it_test_invoice_item_with_tax_price_function()
    {
        $item = [
            'description' => 'Test 1',
            'amount'      => 1,
            'tax_rate'    => 20,
            'price'       => 20,
        ];

        $this->assertEquals(24, invoice_item_with_tax_price($item));
    }

    /**
     * @test
     */
    public function it_test_invoice_total_net()
    {
        $invoice = [
            'discount_type' => null,
            'currency'      => 'CZK',
            'items'         => [
                [
                    'description' => 'Test 1',
                    'amount'      => 1,
                    'tax_rate'    => 20,
                    'price'       => 20,
                ],
                [
                    'description' => 'Test 2',
                    'amount'      => 3,
                    'tax_rate'    => 20,
                    'price'       => 50,
                ],
            ]
        ];

        $this->assertEquals(204, invoice_total($invoice));
    }

    /**
     * @test
     */
    public function it_test_invoice_total_tax()
    {
        $invoice = [
            'currency'      => 'CZK',
            'discount_type' => 'value',
            'discount_rate' => 18,
            'items'         => [
                [
                    'description' => 'Test 1',
                    'amount'      => 1,
                    'tax_rate'    => 20,
                    'price'       => 100,
                ],
                [
                    'description' => 'Test 2',
                    'amount'      => 2,
                    'tax_rate'    => 10,
                    'price'       => 100,
                ],
            ]
        ];

        $this->assertEquals(40, invoice_total_tax($invoice));
    }

    /**
     * @test
     */
    public function it_test_invoice_factory()
    {
        $invoice_profile = InvoiceProfile::factory(InvoiceProfile::class)
            ->create();

        $invoice = Invoice::factory(Invoice::class)
            ->create(['user' => $invoice_profile->toArray()]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
        ]);

        $this->assertDatabaseMissing('invoices', [
            'user' => null,
        ]);
    }

    /**
     * @test
     */
    public function user_create_new_invoice_with_storing_new_client()
    {
        Notification::fake();
        Storage::fake('local');
        PDF::fake();

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'        => 'regular-invoice',
            'invoice_number'      => '2120001',
            'variable_number'     => '2120001',
            'items'               => json_encode($this->items),
            'discount_type'       => 'percent',
            'discount_rate'       => 10,
            'delivery_at'         => Carbon::now()->addWeek(),
            'store_client'        => true,
            'send_invoice'        => true,
            'client'              => 'others',
            'client_avatar'       => $avatar,
            'client_name'         => 'VueFileManager Inc.',
            'client_email'        => 'howdy@hi5ve.digital',
            'client_phone_number' => '+421 950 123 456',
            'client_address'      => 'Does 12',
            'client_city'         => 'Bratislava',
            'client_postal_code'  => '076 54',
            'client_country'      => 'SK',
            'client_ico'          => 11111111,
            'client_dic'          => 11111111,
            'client_ic_dph'       => 'SK11111111',
        ])->assertStatus(201);

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => '2120001',
            'user_id'        => $user->id,
            'items'          => json_encode($this->items),
            'client'         => json_encode($this->client),
        ]);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name'    => 'VueFileManager Inc.',
            'email'   => 'howdy@hi5ve.digital',
        ]);

        Storage::disk('local')
            ->assertExists(
                Client::first()->getRawOriginal('avatar')
            );

        PDF::assertFileNameIs(storage_path("app/" . invoice_path(Invoice::first())));

        Notification::assertTimesSent(1, InvoiceDeliveryNotification::class);
    }

    /**
     * @test
     */
    public function user_create_new_invoice_with_storing_new_client_without_avatar_and_mail()
    {
        Storage::fake('local');
        Notification::fake();
        PDF::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'        => 'regular-invoice',
            'invoice_number'      => '2120001',
            'variable_number'     => '2120001',
            'items'               => json_encode($this->items),
            'discount_type'       => 'percent',
            'discount_rate'       => 10,
            'delivery_at'         => Carbon::now()->addWeek(),
            'store_client'        => true,
            'send_invoice'        => true,
            'client'              => 'others',
            'client_avatar'       => null,
            'client_name'         => 'VueFileManager Inc.',
            'client_email'        => null,
            'client_phone_number' => '+421 950 123 456',
            'client_address'      => 'Does 12',
            'client_city'         => 'Bratislava',
            'client_postal_code'  => '076 54',
            'client_country'      => 'SK',
            'client_ico'          => 11111111,
            'client_dic'          => 11111111,
            'client_ic_dph'       => 'SK11111111',
        ])->assertStatus(201);

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => '2120001',
            'user_id'        => $user->id,
            'items'          => json_encode($this->items),
        ]);

        $this->assertDatabaseHas('clients', [
            'user_id' => $user->id,
            'name'    => 'VueFileManager Inc.',
        ]);

        PDF::assertFileNameIs(storage_path("app/" . invoice_path(Invoice::first())));

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function user_create_new_invoice_without_storing_client_without_avatar_and_mail()
    {
        Storage::fake('local');
        Notification::fake();
        PDF::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'        => 'regular-invoice',
            'invoice_number'      => '2120001',
            'variable_number'     => '2120001',
            'items'               => json_encode($this->items),
            'discount_type'       => 'percent',
            'discount_rate'       => 10,
            'delivery_at'         => Carbon::now()->addWeek(),
            'store_client'        => false,
            'send_invoice'        => false,
            'client'              => 'others',
            'client_avatar'       => null,
            'client_name'         => 'VueFileManager Inc.',
            'client_email'        => 'howdy@hi5ve.digital',
            'client_phone_number' => '+421 950 123 456',
            'client_address'      => 'Does 12',
            'client_city'         => 'Bratislava',
            'client_postal_code'  => '076 54',
            'client_country'      => 'SK',
            'client_ico'          => 11111111,
            'client_dic'          => 11111111,
            'client_ic_dph'       => 'SK11111111',
        ])->assertStatus(201);

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => '2120001',
            'user_id'        => $user->id,
            'items'          => json_encode($this->items),
            'client'         => json_encode($this->client),
        ]);

        $this->assertDatabaseMissing('clients', [
            'user_id' => $user->id,
            'name'    => 'VueFileManager Inc.',
        ]);

        PDF::assertFileNameIs(storage_path("app/" . invoice_path(Invoice::first())));

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function user_create_new_invoice_without_storing_client()
    {
        Storage::fake('local');
        Notification::fake();
        PDF::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'        => 'regular-invoice',
            'invoice_number'      => '2120001',
            'variable_number'     => '2120001',
            'items'               => json_encode($this->items),
            'discount_type'       => 'percent',
            'discount_rate'       => 10,
            'delivery_at'         => Carbon::now()->addWeek(),
            'store_client'        => false,
            'send_invoice'        => true,
            'client'              => 'others',
            'client_name'         => 'VueFileManager Inc.',
            'client_email'        => 'howdy@hi5ve.digital',
            'client_phone_number' => '+421 950 123 456',
            'client_address'      => 'Does 12',
            'client_city'         => 'Bratislava',
            'client_postal_code'  => '076 54',
            'client_country'      => 'SK',
            'client_ico'          => 11111111,
            'client_dic'          => 11111111,
            'client_ic_dph'       => 'SK11111111',
        ])->assertStatus(201);

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => '2120001',
            'user_id'        => $user->id,
            'items'          => json_encode($this->items),
            'client'         => json_encode($this->client),
        ]);

        $this->assertDatabaseMissing('clients', [
            'name'  => 'VueFileManager Inc.',
            'email' => 'howdy@hi5ve.digital',
        ]);

        PDF::assertFileNameIs(storage_path("app/" . invoice_path(Invoice::first())));

        Notification::assertTimesSent(1, InvoiceDeliveryNotification::class);
    }

    /**
     * @test
     */
    public function user_create_new_invoice_with_existing_client()
    {
        Storage::fake('local');
        Notification::fake();
        PDF::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create([
                'user_id' => $user->id,
                'name'    => 'VueFileManager Inc.',
                'email'   => 'howdy@hi5ve.digital',
            ]);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'    => 'regular-invoice',
            'invoice_number'  => '2120001',
            'variable_number' => '2120001',
            'client'          => $client->id,
            'items'           => json_encode($this->items),
            'discount_type'   => 'percent',
            'discount_rate'   => 10,
            'delivery_at'     => Carbon::now()->addWeek(),
            'send_invoice'    => true,
        ])->assertStatus(201);

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => '2120001',
            'user_id'        => $user->id,
            'client_id'      => $client->id,
            'items'          => json_encode($this->items),
        ]);

        PDF::assertFileNameIs(storage_path("app/" . invoice_path(Invoice::first())));

        Notification::assertTimesSent(1, InvoiceDeliveryNotification::class);
    }

    /**
     * @test
     */
    public function user_delete_invoice()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $profile = InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'regular-invoice',
                'user'         => $profile->toArray(),
            ]);

        \PDF::loadView('oasis.invoices.invoice', [
            'invoice' => Invoice::find($invoice->id),
            'user'    => $user,
        ])
            ->setPaper('a4')
            ->setOrientation('portrait')
            ->save(
                storage_path("app/files/{$invoice->user_id}/invoice-{$invoice->id}.pdf")
            );

        Storage::disk('local')
            ->assertExists(
                invoice_path($invoice)
            );

        $this->delete("/api/oasis/invoices/$invoice->id");

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id
        ]);

        Storage::disk('local')
            ->assertMissing(
                invoice_path($invoice)
            );
    }

    /**
     * @test
     */
    public function it_get_invoice_from_url()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $client = Client::factory(Client::class)
            ->create([
                'user_id' => $user->id,
                'name'    => 'VueFileManager Inc.',
                'email'   => 'howdy@hi5ve.digital',
            ]);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'    => 'regular-invoice',
            'invoice_number'  => '2120001',
            'variable_number' => '2120001',
            'client'          => $client->id,
            'items'           => json_encode($this->items),
            'discount_type'   => 'percent',
            'discount_rate'   => 10,
            'delivery_at'     => Carbon::now()->addWeek(),
            'send_invoice'    => true,
        ])->assertStatus(201);

        $invoice = Invoice::first();

        $this->get("/oasis/invoice/{$invoice->id}")
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_all_user_regular_invoices()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $profile = InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'regular-invoice',
                'user'         => $profile,
            ]);

        $this->getJson('/api/oasis/invoices/regular')
            ->assertJsonFragment([
                'id' => $invoice->id,
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_all_user_advance_invoices()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $profile = InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'advance-invoice',
                'user'         => $profile,
            ]);

        $this->getJson('/api/oasis/invoices/advance')
            ->assertJsonFragment([
                'id' => $invoice->id,
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_search_user_regular_invoice()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        $profile = InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        Invoice::factory(Invoice::class)
            ->create([
                'user_id'        => $user->id,
                'invoice_type'   => 'regular-invoice',
                'invoice_number' => 2001212,
                'client'         => [
                    'name' => 'VueFileManager Inc.',
                ],
                'user'           => $profile,
            ]);

        $this->getJson('/api/oasis/invoices/search?type=regular-invoice&query=2001212')
            ->assertJsonFragment([
                'invoiceNumber' => '2001212',
            ])->assertStatus(200);

        $this->getJson('/api/oasis/invoices/search?type=regular-invoice&query=Vue')
            ->assertJsonFragment([
                'invoiceNumber' => '2001212',
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_get_data_for_invoice_editor()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        $client = Client::factory(Client::class)
            ->count(2)
            ->create(['user_id' => $user->id]);

        $client->pluck('id')
            ->each(function ($id) {
                $this->getJson('/api/oasis/invoices/editor')
                    ->assertStatus(200)
                    ->assertJsonFragment([
                        'value'                    => $id,
                        'recommendedInvoiceNumber' => '20210001',
                    ]);
            });
    }

    /**
     * @test
     */
    public function it_get_default_recommended_invoice_number()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        $this->getJson('/api/oasis/invoices/editor')
            ->assertStatus(200)
            ->assertJsonFragment([
                'latestInvoiceNumber'      => null,
                'recommendedInvoiceNumber' => '20210001',
            ]);
    }

    /**
     * @test
     */
    public function it_get_next_invoice_number_after_latest_invoice_number()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        InvoiceProfile::factory(InvoiceProfile::class)
            ->create(['user_id' => $user->id]);

        Invoice::factory(Invoice::class)
            ->create([
                'user_id'        => $user->id,
                'invoice_type'   => 'regular-invoice',
                'invoice_number' => 20210001,
                'client'         => [
                    'name' => 'VueFileManager Inc.',
                ],
                'user'           => 'test',
            ]);

        $this->getJson('/api/oasis/invoices/editor')
            ->assertStatus(200)
            ->assertJsonFragment([
                'latestInvoiceNumber'      => 20210001,
                'recommendedInvoiceNumber' => 20210002,
            ]);
    }
}
