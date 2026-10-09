<?php
namespace Tests\Support\Miscellaneous\Stripe;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\Mocking\Stripe\GetPlanStripeMocksClass;
use Tests\Mocking\Stripe\CreateCustomerStripeMocksClass;
use Tests\Mocking\Stripe\CreateSetupIntentStripeMockClass;
use Tests\Mocking\Stripe\CreateCheckoutSessionStripeMockClass;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;

class StripeMiscellaneousTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_stripe_checkout_session()
    {
        $user = User::factory()
            ->create();

        resolve(CreateCustomerStripeMocksClass::class)($user);
        resolve(CreateCheckoutSessionStripeMockClass::class)();
        resolve(GetPlanStripeMocksClass::class)();

        $this
            ->actingAs($user)
            ->post('/api/stripe/checkout', [
                'planCode' => 'prod_HKL7vHEYRSC4Ur',
            ])
            ->assertCreated()
            ->assertJsonFragment([
                'url' => 'https://checkout.stripe.com/pay/cs_test_a1KLRKWfnXzfewSF8Yk',
            ]);
    }

    /**
     * @test
     */
    public function it_create_stripe_setup_intent()
    {
        $user = User::factory()
            ->create();

        resolve(CreateCustomerStripeMocksClass::class)($user);
        resolve(CreateSetupIntentStripeMockClass::class)();

        $this
            ->actingAs($user)
            ->getJson('/api/stripe/setup-intent')
            ->assertCreated()
            ->assertJsonFragment([
                'client_secret' => 'seti_1KBhcBB9m4sTKy1q52kDOR9I_secret_KrQTiN7KGYtLKAjj2BybzRpgUcpizMq',
            ]);
    }

    /**
     * @test
     */
    public function it_delete_stripe_credit_card()
    {
        $user = User::factory()
            ->create();

        $creditCard = CreditCard::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        Http::fake([
            'https://api.stripe.com/v1/payment_methods/*/detach' => Http::response([
                'id'              => 'pm_1KBx59B9m4sTKy1qf9CPUuQL',
                'object'          => 'payment_method',
                'billing_details' => [
                    'address' => [
                        'city'        => null,
                        'country'     => 'SK',
                        'line1'       => null,
                        'line2'       => null,
                        'postal_code' => null,
                        'state'       => null,
                    ],
                    'email'   => null,
                    'name'    => null,
                    'phone'   => null,
                ],
                'card'            => [
                    'brand'                => 'visa',
                    'checks'               => [
                        'address_line1_check'       => null,
                        'address_postal_code_check' => null,
                        'cvc_check'                 => 'pass',
                    ],
                    'country'              => 'US',
                    'exp_month'            => 11,
                    'exp_year'             => 2022,
                    'fingerprint'          => 'rQCgh6fjRkVkJKgr',
                    'funding'              => 'credit',
                    'generated_from'       => null,
                    'last4'                => '4242',
                    'networks'             => [
                        'available' => [
                            0 => 'visa',
                        ],
                        'preferred' => null,
                    ],
                    'three_d_secure_usage' => [
                        'supported' => true,
                    ],
                    'wallet'               => null,
                ],
                'created'         => 1640764824,
                'customer'        => null,
                'livemode'        => false,
                'metadata'        => [],
                'type'            => 'card',
            ]),
        ]);

        $this
            ->actingAs($user)
            ->delete("/api/stripe/credit-cards/$creditCard->id")
            ->assertOk();

        $this->assertModelMissing($creditCard);
    }
}
