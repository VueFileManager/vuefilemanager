<?php
namespace Tests\Domain\FailedPayments;

use Tests\TestCase;
use Tests\Models\User;
use VueFileManager\Subscription\Domain\FailedPayments\Models\FailedPayment;

class FailedPaymentTest extends TestCase
{
    /**
     * @test
     */
    public function it_retry_withdrawn_after_balance_was_increased()
    {
        $user = User::factory()
            ->hasBalance([
                'amount'   => 5.00,
                'currency' => 'USD',
            ])
            ->create();

        $failedPayment = FailedPayment::factory()
            ->create([
                'user_id'  => $user->id,
                'amount'   => 10.25,
                'currency' => 'USD',
                'note'     => 'today is payday!',
                'metadata' => [
                    [
                        'feature' => 'bandwidth',
                        'amount'  => 10.25,
                        'usage'   => 30,
                    ],
                ],
            ]);

        $user->creditBalance(50.00, 'USD');

        $this
            ->assertDatabaseHas('balances', [
                'user_id'  => $user->id,
                'amount'   => 44.75,
                'currency' => 'USD',
            ])
            ->assertDatabaseHas('transactions', [
                'user_id'  => $user->id,
                'type'     => 'withdrawal',
                'status'   => 'completed',
                'currency' => 'USD',
                'amount'   => 10.25,
                'note'     => 'today is payday!',
                'metadata' => json_encode([
                    [
                        'feature' => 'bandwidth',
                        'amount'  => 10.25,
                        'usage'   => 30,
                    ],
                ]),
            ])
            ->assertModelMissing($failedPayment);
    }
}
