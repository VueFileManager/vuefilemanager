<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Client;
use App\Notifications\Oasis\InvoiceDeliveryNotification;
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
                'description' => 'Test 1',
                'amount'      => 1,
                'tax_rate'    => 20,
                'price'       => 200,
            ],
            [
                'description' => 'Test 2',
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
        $this->assertEquals('4,00 Kč', invoice_item_only_tax_price($item, true));
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
        $this->assertEquals('24,00 Kč', invoice_item_with_tax_price($item, true));
    }

    /**
     * @test
     */
    public function it_test_invoice_total_net()
    {
        $invoice = [
            'currency' => 'CZK',
            'items'    => [
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

        $this->assertEquals(170, invoice_total_net($invoice));
        $this->assertEquals('170,00 Kč', invoice_total_net($invoice, true));
    }

    /**
     * @test
     */
    public function it_test_invoice_total_discount_as_percent()
    {
        $invoice = [
            'currency'      => 'CZK',
            'discount_type' => 'percent',
            'discount_rate' => 15,
            'items'         => [
                [
                    'description' => 'Test 1',
                    'amount'      => 1,
                    'tax_rate'    => 20,
                    'price'       => 200,
                ],
            ]
        ];

        $this->assertEquals(30, invoice_total_discount($invoice));
        $this->assertEquals('30,00 Kč', invoice_total_discount($invoice, true));
    }

    /**
     * @test
     */
    public function it_test_invoice_total_discount_as_value()
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
            ]
        ];

        $this->assertEquals(18, invoice_total_discount($invoice));
        $this->assertEquals('18,00 Kč', invoice_total_discount($invoice, true));
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
        $this->assertEquals('40,00 Kč', invoice_total_tax($invoice, true));
    }

    /**
     * @test
     */
    public function it_test_invoice_factory()
    {
        $invoice = Invoice::factory(Invoice::class)
            ->create();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
        ]);
    }

    /**
     * @test
     */
    public function user_create_new_invoice_with_storing_new_client()
    {
        Notification::fake();

        Storage::fake('local');

        $avatar = UploadedFile::fake()
            ->image('fake-image.jpg');

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'    => 'regular-invoice',
            'invoice_number'  => '2120001',
            'variable_number' => '2120001',
            'items'           => $this->items,
            'discount_type'   => 'percent',
            'discount_rate'   => 10,
            'delivery_at'     => Carbon::now()->addWeek(),
            'store_client'    => true,
            'send_invoice'    => true,

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

        Notification::assertTimesSent(1, InvoiceDeliveryNotification::class);
    }

    /**
     * @test
     */
    public function user_create_new_invoice_with_storing_new_client_without_avatar_and_mail()
    {
        Notification::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'    => 'regular-invoice',
            'invoice_number'  => '2120001',
            'variable_number' => '2120001',
            'items'           => $this->items,
            'discount_type'   => 'percent',
            'discount_rate'   => 10,
            'delivery_at'     => Carbon::now()->addWeek(),
            'store_client'    => true,
            'send_invoice'    => true,

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

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function user_create_new_invoice_without_storing_client_without_avatar_and_mail()
    {
        Notification::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'    => 'regular-invoice',
            'invoice_number'  => '2120001',
            'variable_number' => '2120001',
            'items'           => $this->items,
            'discount_type'   => 'percent',
            'discount_rate'   => 10,
            'delivery_at'     => Carbon::now()->addWeek(),
            'store_client'    => false,
            'send_invoice'    => false,

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

        Notification::assertNothingSent();
    }

    /**
     * @test
     */
    public function user_create_new_invoice_without_storing_client()
    {
        Notification::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $this->postJson('/api/oasis/invoices', [
            'invoice_type'    => 'regular-invoice',
            'invoice_number'  => '2120001',
            'variable_number' => '2120001',
            'items'           => $this->items,
            'discount_type'   => 'percent',
            'discount_rate'   => 10,
            'delivery_at'     => Carbon::now()->addWeek(),
            'store_client'    => false,
            'send_invoice'    => true,

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

        Notification::assertTimesSent(1, InvoiceDeliveryNotification::class);
    }

    /**
     * @test
     */
    public function user_create_new_invoice_with_existing_client()
    {
        Notification::fake();

        $user = User::factory(User::class)
            ->create(['role' => 'user']);

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
            'items'           => $this->items,
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

        Notification::assertTimesSent(1, InvoiceDeliveryNotification::class);
    }

    /**
     * @test
     */
    public function it_get_all_user_regular_invoices()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'regular-invoice'
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

        Sanctum::actingAs($user);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'advance-invoice'
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

        Sanctum::actingAs($user);

        Invoice::factory(Invoice::class)
            ->create([
                'user_id'        => $user->id,
                'invoice_type'   => 'regular-invoice',
                'invoice_number' => 2001212,
                'client'         => [
                    'name' => 'VueFileManager Inc.',
                ]
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
}
