<?php

namespace Tests\Feature\Oasis;

use App\Models\Oasis\Invoice;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
}
