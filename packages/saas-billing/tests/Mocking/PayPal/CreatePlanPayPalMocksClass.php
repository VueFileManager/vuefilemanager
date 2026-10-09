<?php
namespace Tests\Mocking\PayPal;

use Illuminate\Support\Facades\Http;

class CreatePlanPayPalMocksClass
{
    public function __invoke($plan)
    {
        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token'      => Http::response([
                'scope'        => 'scope',
                'access_token' => 'jnjleqngtlq3l34jn6l2346n2l4',
                'token_type'   => 'Bearer',
                'app_id'       => 'APP-80W284485P519543T',
                'expires_in'   => 31349,
                'nonce'        => 'nonce',
            ]),
            'https://api-m.sandbox.paypal.com/v1/catalogs/products' => Http::response([
                'id'          => 'P-5ML4271244454362WXNWU5NQ',
                'name'        => 'Subscription Service',
                'description' => 'Cloud subscription service',
                'type'        => 'SERVICE',
                'category'    => 'SOFTWARE',
            ]),
            'https://api-m.sandbox.paypal.com/v1/billing/plans'     => Http::response([
                'id'                  => 'P-5ML4271244454362WXNWU5NQ',
                'product_id'          => 'PROD-XXCD1234QWER65782',
                'name'                => $plan->name,
                'description'         => $plan->description,
                'status'              => 'ACTIVE',
                'billing_cycles'      => [
                    [
                        'frequency'      => [
                            'interval_unit'  => 'MONTH',
                            'interval_count' => 1,
                        ],
                        'tenure_type'    => 'TRIAL',
                        'sequence'       => 1,
                        'total_cycles'   => 2,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'value'         => $plan->amount,
                                'currency_code' => 'USD',
                            ],
                            'version'     => 1,
                            'create_time' => '2020-05-27T12:13:51Z',
                            'update_time' => '2020-05-27T12:13:51Z',
                        ],
                    ],
                    [
                        'frequency'      => [
                            'interval_unit'  => 'MONTH',
                            'interval_count' => 1,
                        ],
                        'tenure_type'    => 'TRIAL',
                        'sequence'       => 2,
                        'total_cycles'   => 3,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'currency_code' => 'USD',
                                'value'         => '6',
                            ],
                            'version'     => 1,
                            'create_time' => '2020-05-27T12:13:51Z',
                            'update_time' => '2020-05-27T12:13:51Z',
                        ],
                    ],
                    [
                        'frequency'      => [
                            'interval_unit'  => 'MONTH',
                            'interval_count' => 1,
                        ],
                        'tenure_type'    => 'REGULAR',
                        'sequence'       => 3,
                        'total_cycles'   => 12,
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'currency_code' => 'USD',
                                'value'         => '10',
                            ],
                            'version'     => 1,
                            'create_time' => '2020-05-27T12:13:51Z',
                            'update_time' => '2020-05-27T12:13:51Z',
                        ],
                    ],
                ],
                'payment_preferences' => [
                    'auto_bill_outstanding'     => true,
                    'setup_fee'                 => [
                        'value'         => '10',
                        'currency_code' => 'USD',
                    ],
                    'setup_fee_failure_action'  => 'CONTINUE',
                    'payment_failure_threshold' => 3,
                ],
                'taxes'               => [
                    'percentage' => '10',
                    'inclusive'  => false,
                ],
                'create_time'         => '2020-05-27T12:13:51Z',
                'update_time'         => '2020-05-27T12:13:51Z',
            ]),
        ]);
    }
}
