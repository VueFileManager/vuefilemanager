<?php
namespace Tests\Domain\Transactions;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Models\User;

class TransactionsTest extends TestCase
{
    #[Test]
    public function it_get_all_my_transactions()
    {
        $user = User::factory()
            ->hasTransactions(2)
            ->create();

        $this
            ->actingAs($user)
            ->getJson('/api/subscriptions/transactions')
            ->assertOk();
    }
    #[Test]
    public function it_get_all_user_transactions()
    {
        $user = User::factory()
            ->hasTransactions(2)
            ->create(['role' => 'admin']);

        $this
            ->actingAs($user)
            ->getJson("/api/subscriptions/admin/users/{$user->id}/transactions")
            ->assertOk()
            ->assertJsonFragment([
                'id' => $user->transactions->pluck('id')[0],
            ]);
    }
}
