<?php
namespace Tests\Domain\BillingAlerts;

use Tests\TestCase;
use Tests\Models\User;

class BillingAlertTest extends TestCase
{
    /**
     * @test
     */
    public function it_store_billing_alert()
    {
        $user = User::factory()
            ->create();

        $this
            ->actingAs($user)
            ->postJson('/api/subscriptions/billing-alert', [
                'amount' => 30,
            ])->assertCreated();

        $this->assertDatabaseHas('billing_alerts', [
            'user_id' => $user->id,
            'amount'  => 30,
        ]);
    }

    /**
     * @test
     */
    public function it_update_billing_alert()
    {
        $user = User::factory()
            ->create();

        $user->billingAlert()->create([
            'amount' => 20,
        ]);

        $this
            ->actingAs($user)
            ->putJson('/api/subscriptions/billing-alert', [
                'amount' => 30,
            ])->assertOk();

        $this->assertDatabaseHas('billing_alerts', [
            'user_id' => $user->id,
            'amount'  => 30,
        ]);
    }

    /**
     * @test
     */
    public function it_delete_billing_alert()
    {
        $user = User::factory()
            ->create();

        $user->billingAlert()->create([
            'amount' => 20,
        ]);

        $this
            ->actingAs($user)
            ->delete('/api/subscriptions/billing-alert')
            ->assertOk();

        $this->assertModelMissing($user->billingAlert);
    }
}
