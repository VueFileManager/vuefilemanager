<?php
namespace Tests\Domain\FailedPayments;

use Tests\TestCase;
use Tests\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\Helpers\StripeTestHelpers;
use Illuminate\Support\Facades\Notification;
use VueFileManager\Subscription\Domain\Plans\Models\Plan;
use VueFileManager\Subscription\Domain\Customers\Models\Customer;
use VueFileManager\Subscription\Domain\CreditCards\Models\CreditCard;
use VueFileManager\Subscription\Domain\Subscriptions\Models\Subscription;
use VueFileManager\Subscription\Domain\FailedPayments\Models\FailedPayment;
use VueFileManager\Subscription\Domain\FailedPayments\Actions\RetryChargeFromPaymentCardAction;
use VueFileManager\Subscription\Domain\FailedPayments\Notifications\ChargeFromCreditCardFailedAgainNotification;

class StripeFailedPaymentTest extends TestCase
{
    use StripeTestHelpers;

    /**
     * @test
     */
    public function it_retry_charge_from_payment_card_with_failed_result()
    {
        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        Subscription::factory()
            ->create([
                'type'       => 'pre-paid',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
            ]);

        CreditCard::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $failedPayment = FailedPayment::factory()
            ->create([
                'user_id'  => $user->id,
                'currency' => 'USD',
                'amount'   => 24.59,
                'source'   => 'credit-card',
                'attempts' => 2,
            ]);

        Http::fake([
            'https://api.stripe.com/v1/payment_intents' => Http::response([
                'error' => [
                    'charge'         => 'ch_3KC56EB9m4sTKy1q1rqtEjhY',
                    'code'           => 'card_declined',
                    'decline_code'   => 'generic_decline',
                    'doc_url'        => 'https://stripe.com/docs/error-codes/card-declined',
                    'message'        => 'Your card was declined.',
                    'payment_intent' => [
                        'id'                          => 'pi_3KC56EB9m4sTKy1q1METgag8',
                        'object'                      => 'payment_intent',
                        'amount'                      => 337,
                        'amount_capturable'           => 0,
                        'amount_received'             => 0,
                        'application'                 => null,
                        'application_fee_amount'      => null,
                        'automatic_payment_methods'   => null,
                        'canceled_at'                 => null,
                        'cancellation_reason'         => null,
                        'capture_method'              => 'automatic',
                        'charges'                     => [
                            'object'      => 'list',
                            'data'        => [
                                [
                                    'id'                              => 'ch_3KC56EB9m4sTKy1q1rqtEjhY',
                                    'object'                          => 'charge',
                                    'amount'                          => 337,
                                    'amount_captured'                 => 0,
                                    'amount_refunded'                 => 0,
                                    'application'                     => null,
                                    'application_fee'                 => null,
                                    'application_fee_amount'          => null,
                                    'balance_transaction'             => null,
                                    'billing_details'                 => [
                                        'address' => [
                                            'city'        => null,
                                            'country'     => null,
                                            'line1'       => null,
                                            'line2'       => null,
                                            'postal_code' => null,
                                            'state'       => null,
                                        ],
                                        'email'   => null,
                                        'name'    => null,
                                        'phone'   => null,
                                    ],
                                    'calculated_statement_descriptor' => 'Stripe',
                                    'captured'                        => false,
                                    'created'                         => 1640795643,
                                    'currency'                        => 'usd',
                                    'customer'                        => 'cus_KrgRc2TH3yh3xC',
                                    'description'                     => null,
                                    'destination'                     => null,
                                    'dispute'                         => null,
                                    'disputed'                        => false,
                                    'failure_code'                    => 'card_declined',
                                    'failure_message'                 => 'Your card was declined.',
                                    'fraud_details'                   => [
                                    ],
                                    'invoice'                         => null,
                                    'livemode'                        => false,
                                    'metadata'                        => [
                                    ],
                                    'on_behalf_of'                    => null,
                                    'order'                           => null,
                                    'outcome'                         => [
                                        'network_status' => 'declined_by_network',
                                        'reason'         => 'generic_decline',
                                        'risk_level'     => 'normal',
                                        'risk_score'     => 31,
                                        'seller_message' => 'The bank did not return any further details with this decline.',
                                        'type'           => 'issuer_declined',
                                    ],
                                    'paid'                            => false,
                                    'payment_intent'                  => 'pi_3KC56EB9m4sTKy1q1METgag8',
                                    'payment_method'                  => 'card_1KC53vB9m4sTKy1qKACuKxY8',
                                    'payment_method_details'          => [
                                        'card' => [
                                            'brand'          => 'visa',
                                            'checks'         => [
                                                'address_line1_check'       => null,
                                                'address_postal_code_check' => null,
                                                'cvc_check'                 => 'pass',
                                            ],
                                            'country'        => 'US',
                                            'exp_month'      => 11,
                                            'exp_year'       => 2022,
                                            'fingerprint'    => '1hTXNYwi81P7aiRM',
                                            'funding'        => 'credit',
                                            'installments'   => null,
                                            'last4'          => '0341',
                                            'network'        => 'visa',
                                            'three_d_secure' => null,
                                            'wallet'         => null,
                                        ],
                                        'type' => 'card',
                                    ],
                                    'receipt_email'                   => null,
                                    'receipt_number'                  => null,
                                    'receipt_url'                     => null,
                                    'refunded'                        => false,
                                    'refunds'                         => [
                                        'object'      => 'list',
                                        'data'        => [
                                        ],
                                        'has_more'    => false,
                                        'total_count' => 0,
                                        'url'         => '/v1/charges/ch_3KC56EB9m4sTKy1q1rqtEjhY/refunds',
                                    ],
                                    'review'                          => null,
                                    'shipping'                        => null,
                                    'source'                          => null,
                                    'source_transfer'                 => null,
                                    'statement_descriptor'            => null,
                                    'statement_descriptor_suffix'     => null,
                                    'status'                          => 'failed',
                                    'transfer_data'                   => null,
                                    'transfer_group'                  => null,
                                ],
                            ],
                            'has_more'    => false,
                            'total_count' => 1,
                            'url'         => '/v1/charges?payment_intent=pi_3KC56EB9m4sTKy1q1METgag8',
                        ],
                        'client_secret'               => 'pi_3KC56EB9m4sTKy1q1METgag8_secret_2B01k71myaJbApjmRtQtzWrIh',
                        'confirmation_method'         => 'automatic',
                        'created'                     => 1640795642,
                        'currency'                    => 'usd',
                        'customer'                    => 'cus_KrgRc2TH3yh3xC',
                        'description'                 => null,
                        'invoice'                     => null,
                        'last_payment_error'          => [
                            'charge'         => 'ch_3KC56EB9m4sTKy1q1rqtEjhY',
                            'code'           => 'card_declined',
                            'decline_code'   => 'generic_decline',
                            'doc_url'        => 'https://stripe.com/docs/error-codes/card-declined',
                            'message'        => 'Your card was declined.',
                            'payment_method' => [
                                'id'              => 'card_1KC53vB9m4sTKy1qKACuKxY8',
                                'object'          => 'payment_method',
                                'billing_details' => [
                                    'address' => [
                                        'city'        => null,
                                        'country'     => null,
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
                                    'fingerprint'          => '1hTXNYwi81P7aiRM',
                                    'funding'              => 'credit',
                                    'generated_from'       => null,
                                    'last4'                => '0341',
                                    'networks'             => [
                                        'available' => [
                                            'visa',
                                        ],
                                        'preferred' => null,
                                    ],
                                    'three_d_secure_usage' => [
                                        'supported' => true,
                                    ],
                                    'wallet'               => null,
                                ],
                                'created'         => 1640795499,
                                'customer'        => 'cus_KrgRc2TH3yh3xC',
                                'livemode'        => false,
                                'metadata'        => [
                                ],
                                'type'            => 'card',
                            ],
                            'type'           => 'card_error',
                        ],
                        'livemode'                    => false,
                        'metadata'                    => [
                        ],
                        'next_action'                 => null,
                        'on_behalf_of'                => null,
                        'payment_method'              => null,
                        'payment_method_options'      => [
                            'card' => [
                                'installments'           => null,
                                'network'                => null,
                                'request_three_d_secure' => 'automatic',
                            ],
                        ],
                        'payment_method_types'        => [
                            'card',
                        ],
                        'processing'                  => null,
                        'receipt_email'               => null,
                        'review'                      => null,
                        'setup_future_usage'          => null,
                        'shipping'                    => null,
                        'source'                      => null,
                        'statement_descriptor'        => null,
                        'statement_descriptor_suffix' => null,
                        'status'                      => 'requires_payment_method',
                        'transfer_data'               => null,
                        'transfer_group'              => null,
                    ],
                    'payment_method' => [
                        'id'              => 'card_1KC53vB9m4sTKy1qKACuKxY8',
                        'object'          => 'payment_method',
                        'billing_details' => [
                            'address' => [
                                'city'        => null,
                                'country'     => null,
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
                            'fingerprint'          => '1hTXNYwi81P7aiRM',
                            'funding'              => 'credit',
                            'generated_from'       => null,
                            'last4'                => '0341',
                            'networks'             => [
                                'available' => [
                                    'visa',
                                ],
                                'preferred' => null,
                            ],
                            'three_d_secure_usage' => [
                                'supported' => true,
                            ],
                            'wallet'               => null,
                        ],
                        'created'         => 1640795499,
                        'customer'        => 'cus_KrgRc2TH3yh3xC',
                        'livemode'        => false,
                        'metadata'        => [
                        ],
                        'type'            => 'card',
                    ],
                    'type'           => 'card_error',
                ],
            ]),
        ]);

        // Retry charge
        resolve(RetryChargeFromPaymentCardAction::class)($user, $failedPayment);

        $this->assertDatabaseHas('failed_payments', [
            'attempts' => 3,
        ]);

        Notification::assertSentTo($user, ChargeFromCreditCardFailedAgainNotification::class);
    }

    /**
     * @test
     */
    public function it_retry_charge_from_payment_card_with_success_result()
    {
        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        Subscription::factory()
            ->create([
                'type'       => 'pre-paid',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
            ]);

        CreditCard::factory()
            ->create([
                'user_id' => $user->id,
            ]);

        $failedPayment = FailedPayment::factory()
            ->create([
                'user_id'  => $user->id,
                'currency' => 'USD',
                'amount'   => 24.59,
                'source'   => 'credit-card',
                'note'     => 'today',
                'metadata' => [
                    [
                        'feature' => 'bandwidth',
                        'amount'  => 24.59,
                        'usage'   => 30,
                    ],
                ],
            ]);

        Http::fake([
            'https://api.stripe.com/v1/payment_intents' => Http::response([
                'id'                          => 'pi_3KBzpsB9m4sTKy1q1AOy8LPj',
                'object'                      => 'payment_intent',
                'amount'                      => 336,
                'amount_capturable'           => 0,
                'amount_received'             => 336,
                'application'                 => null,
                'application_fee_amount'      => null,
                'automatic_payment_methods'   => null,
                'canceled_at'                 => null,
                'cancellation_reason'         => null,
                'capture_method'              => 'automatic',
                'charges'                     => [
                    'object'      => 'list',
                    'data'        => [
                        [
                            'id'                              => 'ch_3KBzpsB9m4sTKy1q1BAQe74u',
                            'object'                          => 'charge',
                            'amount'                          => 336,
                            'amount_captured'                 => 336,
                            'amount_refunded'                 => 0,
                            'application'                     => null,
                            'application_fee'                 => null,
                            'application_fee_amount'          => null,
                            'balance_transaction'             => 'txn_3KBzpsB9m4sTKy1q1xEVKFOI',
                            'billing_details'                 => [
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
                            'calculated_statement_descriptor' => 'Stripe',
                            'captured'                        => true,
                            'created'                         => 1640775408,
                            'currency'                        => 'usd',
                            'customer'                        => 'cus_KrgRc2TH3yh3xC',
                            'description'                     => null,
                            'destination'                     => null,
                            'dispute'                         => null,
                            'disputed'                        => false,
                            'failure_code'                    => null,
                            'failure_message'                 => null,
                            'fraud_details'                   => [
                            ],
                            'invoice'                         => null,
                            'livemode'                        => false,
                            'metadata'                        => [
                            ],
                            'on_behalf_of'                    => null,
                            'order'                           => null,
                            'outcome'                         => [
                                'network_status' => 'approved_by_network',
                                'reason'         => null,
                                'risk_level'     => 'normal',
                                'risk_score'     => 58,
                                'seller_message' => 'Payment complete.',
                                'type'           => 'authorized',
                            ],
                            'paid'                            => true,
                            'payment_intent'                  => 'pi_3KBzpsB9m4sTKy1q1AOy8LPj',
                            'payment_method'                  => 'pm_1KBzo4B9m4sTKy1q1GW82ZfG',
                            'payment_method_details'          => [
                                'card' => [
                                    'brand'          => 'visa',
                                    'checks'         => [
                                        'address_line1_check'       => null,
                                        'address_postal_code_check' => null,
                                        'cvc_check'                 => 'pass',
                                    ],
                                    'country'        => 'US',
                                    'exp_month'      => 11,
                                    'exp_year'       => 2022,
                                    'fingerprint'    => 'rQCgh6fjRkVkJKgr',
                                    'funding'        => 'credit',
                                    'installments'   => null,
                                    'last4'          => '4242',
                                    'network'        => 'visa',
                                    'three_d_secure' => null,
                                    'wallet'         => null,
                                ],
                                'type' => 'card',
                            ],
                            'receipt_email'                   => null,
                            'receipt_number'                  => null,
                            'receipt_url'                     => 'https://pay.stripe.com/receipts/acct_1K1tczB9m4sTKy1q/ch_3KBzpsB9m4sTKy1q1BAQe74u/rcpt_KrjIb3T6ysPclGyvbzpKfhmetEYc9Uk',
                            'refunded'                        => false,
                            'refunds'                         => [
                                'object'      => 'list',
                                'data'        => [
                                ],
                                'has_more'    => false,
                                'total_count' => 0,
                                'url'         => '/v1/charges/ch_3KBzpsB9m4sTKy1q1BAQe74u/refunds',
                            ],
                            'review'                          => null,
                            'shipping'                        => null,
                            'source'                          => null,
                            'source_transfer'                 => null,
                            'statement_descriptor'            => null,
                            'statement_descriptor_suffix'     => null,
                            'status'                          => 'succeeded',
                            'transfer_data'                   => null,
                            'transfer_group'                  => null,
                        ],
                    ],
                    'has_more'    => false,
                    'total_count' => 1,
                    'url'         => '/v1/charges?payment_intent=pi_3KBzpsB9m4sTKy1q1AOy8LPj',
                ],
                'client_secret'               => 'pi_3KBzpsB9m4sTKy1q1AOy8LPj_secret_HW3MoGbGLWKzcSRKiVpShmKty',
                'confirmation_method'         => 'automatic',
                'created'                     => 1640775408,
                'currency'                    => 'usd',
                'customer'                    => 'cus_KrgRc2TH3yh3xC',
                'description'                 => null,
                'invoice'                     => null,
                'last_payment_error'          => null,
                'livemode'                    => false,
                'metadata'                    => [
                ],
                'next_action'                 => null,
                'on_behalf_of'                => null,
                'payment_method'              => 'pm_1KBzo4B9m4sTKy1q1GW82ZfG',
                'payment_method_options'      => [
                    'card' => [
                        'installments'           => null,
                        'network'                => null,
                        'request_three_d_secure' => 'automatic',
                    ],
                ],
                'payment_method_types'        => [
                    'card',
                ],
                'processing'                  => null,
                'receipt_email'               => null,
                'review'                      => null,
                'setup_future_usage'          => null,
                'shipping'                    => null,
                'source'                      => null,
                'statement_descriptor'        => null,
                'statement_descriptor_suffix' => null,
                'status'                      => 'succeeded',
                'transfer_data'               => null,
                'transfer_group'              => null,
            ]),
        ]);

        // Retry charge
        resolve(RetryChargeFromPaymentCardAction::class)($user, $failedPayment);

        $this
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'note'      => 'today',
                'currency'  => 'USD',
                'amount'    => 24.59,
                'driver'    => 'stripe',
                'reference' => 'ch_3KBzpsB9m4sTKy1q1BAQe74u',
                'metadata'  => json_encode([
                    [
                        'feature' => 'bandwidth',
                        'amount'  => 24.59,
                        'usage'   => 30,
                    ],
                ]),
            ])
            ->assertModelMissing($failedPayment);
    }

    /**
     * @test
     */
    public function it_retry_charge_when_new_card_is_attached_with_success_result()
    {
        $user = User::factory()
            ->create();

        $plan = Plan::factory()
            ->create([
                'type'     => 'metered',
                'currency' => 'USD',
            ]);

        Subscription::factory()
            ->create([
                'type'       => 'pre-paid',
                'status'     => 'active',
                'plan_id'    => $plan->id,
                'user_id'    => $user->id,
            ]);

        Customer::create([
            'user_id'        => $user->id,
            'driver_user_id' => 'cus_KrgRc2TH3yh3xC',
            'driver'         => 'stripe',
        ]);

        $failedPayment = FailedPayment::factory()
            ->create([
                'user_id'   => $user->id,
                'currency'  => 'USD',
                'amount'    => 24.59,
                'source'    => 'credit-card',
                'note'      => 'today',
                'metadata'  => [
                    [
                        'feature' => 'bandwidth',
                        'amount'  => 24.59,
                        'usage'   => 30,
                    ],
                ],
            ]);

        Http::fake([
            'https://api.stripe.com/v1/payment_intents' => Http::response([
                'id'                          => 'pi_3KBzpsB9m4sTKy1q1AOy8LPj',
                'object'                      => 'payment_intent',
                'amount'                      => 336,
                'amount_capturable'           => 0,
                'amount_received'             => 336,
                'application'                 => null,
                'application_fee_amount'      => null,
                'automatic_payment_methods'   => null,
                'canceled_at'                 => null,
                'cancellation_reason'         => null,
                'capture_method'              => 'automatic',
                'charges'                     => [
                    'object'      => 'list',
                    'data'        => [
                        [
                            'id'                              => 'ch_3KBzpsB9m4sTKy1q1BAQe74u',
                            'object'                          => 'charge',
                            'amount'                          => 336,
                            'amount_captured'                 => 336,
                            'amount_refunded'                 => 0,
                            'application'                     => null,
                            'application_fee'                 => null,
                            'application_fee_amount'          => null,
                            'balance_transaction'             => 'txn_3KBzpsB9m4sTKy1q1xEVKFOI',
                            'billing_details'                 => [
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
                            'calculated_statement_descriptor' => 'Stripe',
                            'captured'                        => true,
                            'created'                         => 1640775408,
                            'currency'                        => 'usd',
                            'customer'                        => 'cus_KrgRc2TH3yh3xC',
                            'description'                     => null,
                            'destination'                     => null,
                            'dispute'                         => null,
                            'disputed'                        => false,
                            'failure_code'                    => null,
                            'failure_message'                 => null,
                            'fraud_details'                   => [
                            ],
                            'invoice'                         => null,
                            'livemode'                        => false,
                            'metadata'                        => [
                            ],
                            'on_behalf_of'                    => null,
                            'order'                           => null,
                            'outcome'                         => [
                                'network_status' => 'approved_by_network',
                                'reason'         => null,
                                'risk_level'     => 'normal',
                                'risk_score'     => 58,
                                'seller_message' => 'Payment complete.',
                                'type'           => 'authorized',
                            ],
                            'paid'                            => true,
                            'payment_intent'                  => 'pi_3KBzpsB9m4sTKy1q1AOy8LPj',
                            'payment_method'                  => 'pm_1KBzo4B9m4sTKy1q1GW82ZfG',
                            'payment_method_details'          => [
                                'card' => [
                                    'brand'          => 'visa',
                                    'checks'         => [
                                        'address_line1_check'       => null,
                                        'address_postal_code_check' => null,
                                        'cvc_check'                 => 'pass',
                                    ],
                                    'country'        => 'US',
                                    'exp_month'      => 11,
                                    'exp_year'       => 2022,
                                    'fingerprint'    => 'rQCgh6fjRkVkJKgr',
                                    'funding'        => 'credit',
                                    'installments'   => null,
                                    'last4'          => '4242',
                                    'network'        => 'visa',
                                    'three_d_secure' => null,
                                    'wallet'         => null,
                                ],
                                'type' => 'card',
                            ],
                            'receipt_email'                   => null,
                            'receipt_number'                  => null,
                            'receipt_url'                     => 'https://pay.stripe.com/receipts/acct_1K1tczB9m4sTKy1q/ch_3KBzpsB9m4sTKy1q1BAQe74u/rcpt_KrjIb3T6ysPclGyvbzpKfhmetEYc9Uk',
                            'refunded'                        => false,
                            'refunds'                         => [
                                'object'      => 'list',
                                'data'        => [
                                ],
                                'has_more'    => false,
                                'total_count' => 0,
                                'url'         => '/v1/charges/ch_3KBzpsB9m4sTKy1q1BAQe74u/refunds',
                            ],
                            'review'                          => null,
                            'shipping'                        => null,
                            'source'                          => null,
                            'source_transfer'                 => null,
                            'statement_descriptor'            => null,
                            'statement_descriptor_suffix'     => null,
                            'status'                          => 'succeeded',
                            'transfer_data'                   => null,
                            'transfer_group'                  => null,
                        ],
                    ],
                    'has_more'    => false,
                    'total_count' => 1,
                    'url'         => '/v1/charges?payment_intent=pi_3KBzpsB9m4sTKy1q1AOy8LPj',
                ],
                'client_secret'               => 'pi_3KBzpsB9m4sTKy1q1AOy8LPj_secret_HW3MoGbGLWKzcSRKiVpShmKty',
                'confirmation_method'         => 'automatic',
                'created'                     => 1640775408,
                'currency'                    => 'usd',
                'customer'                    => 'cus_KrgRc2TH3yh3xC',
                'description'                 => null,
                'invoice'                     => null,
                'last_payment_error'          => null,
                'livemode'                    => false,
                'metadata'                    => [
                ],
                'next_action'                 => null,
                'on_behalf_of'                => null,
                'payment_method'              => 'pm_1KBzo4B9m4sTKy1q1GW82ZfG',
                'payment_method_options'      => [
                    'card' => [
                        'installments'           => null,
                        'network'                => null,
                        'request_three_d_secure' => 'automatic',
                    ],
                ],
                'payment_method_types'        => [
                    'card',
                ],
                'processing'                  => null,
                'receipt_email'               => null,
                'review'                      => null,
                'setup_future_usage'          => null,
                'shipping'                    => null,
                'source'                      => null,
                'statement_descriptor'        => null,
                'statement_descriptor_suffix' => null,
                'status'                      => 'succeeded',
                'transfer_data'               => null,
                'transfer_group'              => null,
            ]),
        ]);

        $payload = [
            'created'          => 1326853478,
            'livemode'         => false,
            'id'               => 'evt_00000000000000',
            'type'             => 'payment_method.attached',
            'object'           => 'event',
            'request'          => null,
            'pending_webhooks' => 1,
            'api_version'      => '2020-08-27',
            'data'             => [
                'object' => [
                    'id'              => 'pm_00000000000000',
                    'object'          => 'payment_method',
                    'billing_details' => [
                        'address' => [
                            'city'        => null,
                            'country'     => null,
                            'line1'       => null,
                            'line2'       => null,
                            'postal_code' => '94107',
                            'state'       => null,
                        ],
                        'email'   => 'jenny@example.com',
                        'name'    => null,
                        'phone'   => '+15555555555',
                    ],
                    'card'            => [
                        'brand'                => 'visa',
                        'checks'               => [
                            'address_line1_check'       => null,
                            'address_postal_code_check' => null,
                            'cvc_check'                 => 'pass',
                        ],
                        'country'              => 'US',
                        'exp_month'            => 8,
                        'exp_year'             => 2022,
                        'fingerprint'          => 'rQCgh6fjRkVkJKgr',
                        'funding'              => 'credit',
                        'generated_from'       => null,
                        'last4'                => '4242',
                        'networks'             => [
                            'available' => [
                                'visa',
                            ],
                            'preferred' => null,
                        ],
                        'three_d_secure_usage' => [
                            'supported' => true,
                        ],
                        'wallet'               => null,
                    ],
                    'created'         => 123456789,
                    'customer'        => 'cus_KrgRc2TH3yh3xC',
                    'livemode'        => false,
                    'metadata'        => [
                        'order_id' => '123456789',
                    ],
                    'type'            => 'card',
                ],
            ],
        ];

        $this
            ->withHeader('Stripe-Signature', $this->generateTestSignature($payload))
            ->postJson('/api/subscriptions/stripe/webhooks', $payload)
            ->assertOk();

        $this
            ->assertDatabaseHas('transactions', [
                'user_id'   => $user->id,
                'type'      => 'charge',
                'status'    => 'completed',
                'note'      => 'today',
                'currency'  => 'USD',
                'amount'    => 24.59,
                'driver'    => 'stripe',
                'reference' => 'ch_3KBzpsB9m4sTKy1q1BAQe74u',
                'metadata'  => json_encode([
                    [
                        'feature' => 'bandwidth',
                        'amount'  => 24.59,
                        'usage'   => 30,
                    ],
                ]),
            ])
            ->assertModelMissing($failedPayment);
    }
}
