<?php
namespace Tests\Support\Helpers;

use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_split_name()
    {
        $firstTest = split_name('Jane Doe');

        $this->assertEquals('Jane', $firstTest['first_name']);
        $this->assertEquals('Doe', $firstTest['last_name']);

        $secondTest = split_name('Jane Doe Hobs');

        $this->assertEquals('Jane', $secondTest['first_name']);
        $this->assertEquals('Doe Hobs', $secondTest['last_name']);

        $thirdTest = split_name('Jane');

        $this->assertEquals('Jane', $thirdTest['first_name']);
        $this->assertEquals('', $thirdTest['last_name']);
    }
}
