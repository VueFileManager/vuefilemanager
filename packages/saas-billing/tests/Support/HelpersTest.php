<?php
namespace Tests\Support;

use stdClass;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_format_currency_function()
    {
        $item = new stdClass;

        $item->amount = 22.99;
        $item->currency = 'USD';

        $this->assertEquals('$22.99', format_currency($item->amount, $item->currency));
    }
}
