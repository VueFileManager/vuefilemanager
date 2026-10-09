<?php
namespace Tests\Support\Miscellaneous\Paystack;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Http;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;

class PaystackMiscellaneousTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_transaction_url_for_subscription_plan()
    {
        $user = User::factory()
            ->create();

        Plan::factory()
            ->hasDrivers([
                'driver'         => 'paypal',
                'driver_plan_id' => 'PLN_7fk2qj6z33a2pw7',
            ])
            ->create();

        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status'  => true,
                'message' => 'Authorization URL created',
                'data'    => [
                    'authorization_url' => 'https://checkout.paystack.com/9cfwul2yhp2ghxi',
                    'access_code'       => '9cfwul2yhp2ghxi',
                    'reference'         => '4yazzhzp00',
                ],
            ]),
        ]);

        $this
            ->actingAs($user)
            ->postJson('/api/paystack/checkout', [
                'planCode' => 'PLN_7fk2qj6z33a2pw7',
            ])
            ->assertJsonFragment([
                'authorization_url' => 'https://checkout.paystack.com/9cfwul2yhp2ghxi',
            ]);
    }
    /**
     * @test
     */
    public function it_create_transaction_url_for_single_charge()
    {
        $user = User::factory()
            ->create();

        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status'  => true,
                'message' => 'Authorization URL created',
                'data'    => [
                    'authorization_url' => 'https://checkout.paystack.com/9cfwul2yhp2ghxi',
                    'access_code'       => '9cfwul2yhp2ghxi',
                    'reference'         => '4yazzhzp00',
                ],
            ]),
        ]);

        $this
            ->actingAs($user)
            ->postJson('/api/paystack/checkout', [
                'amount' => 999,
            ])
            ->assertJsonFragment([
                'authorization_url' => 'https://checkout.paystack.com/9cfwul2yhp2ghxi',
            ]);
    }
}
