<?php
namespace Tests\Domain\CreditCards;

use Tests\TestCase;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;

class CreditCardTest extends TestCase
{
    /**
     * @test
     */
    public function it_check_correctness_of_isBeforeExpiration_and_isExpired_attributes()
    {
        $isBeforeExpiration = CreditCard::factory()
            ->create([
                'expiration' => now()->addDays(15),
            ]);

        $isExpired = CreditCard::factory()
            ->create([
                'expiration' => now()->subDays(15),
            ]);

        $this->assertEquals(true, $isBeforeExpiration->isBeforeExpiration);
        $this->assertEquals(true, $isExpired->isExpired);

        $notBeforeExpiration = CreditCard::factory()
            ->create([
                'expiration' => now()->addDays(45),
            ]);

        $notExpired = CreditCard::factory()
            ->create([
                'expiration' => now()->addDays(45),
            ]);

        $this->assertEquals(false, $notBeforeExpiration->isBeforeExpiration);
        $this->assertEquals(false, $notExpired->isExpired);
    }
}
