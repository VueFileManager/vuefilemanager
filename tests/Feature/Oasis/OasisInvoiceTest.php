<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OasisInvoiceTest extends TestCase
{
    use DatabaseMigrations;

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

        $response = $this->getJson('/api/oasis/invoices/regular')
            ->assertJsonFragment([
                'id'      => $invoice->id,
            ])->assertStatus(200);

        dd(json_decode($response->content(), true));
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
                'id'      => $invoice->id,
            ])->assertStatus(200);
    }
}
