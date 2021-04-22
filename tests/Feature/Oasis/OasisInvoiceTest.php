<?php

namespace Tests\Feature\Oasis;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Oasis\Invoice;
use Illuminate\Bus\Queueable;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;

class OasisInvoiceTest extends TestCase
{
    use DatabaseMigrations, Queueable;

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
    public function it_get_all_user_regular_invoices()
    {
        $user = User::factory(User::class)
            ->create(['role' => 'user']);

        Sanctum::actingAs($user);

        $invoice = Invoice::factory(Invoice::class)
            ->create([
                'user_id'      => $user->id,
                'invoice_type' => 'regular_invoice'
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
                'invoice_type' => 'advance_invoice'
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
                'invoice_type'   => 'regular_invoice',
                'invoice_number' => 2001212,
                'client'         => [
                    'name' => 'VueFileManager Inc.',
                ]
            ]);

        $this->getJson('/api/oasis/invoices/search?type=regular_invoice&query=2001212')
            ->assertJsonFragment([
                'invoiceNumber' => '2001212',
            ])->assertStatus(200);

        $this->getJson('/api/oasis/invoices/search?type=regular_invoice&query=Vue')
            ->assertJsonFragment([
                'invoiceNumber' => '2001212',
            ])->assertStatus(200);
    }
}
