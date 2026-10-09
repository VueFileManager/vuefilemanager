<?php
namespace Tests\Mocking\Stripe;

use Illuminate\Support\Facades\Http;

class GetPlanStripeMocksClass
{
    public function __invoke()
    {
        return Http::fake([
            'https://api.stripe.com/v1/prices/*' => Http::response([
                'id'                  => 'price_1K4pY4B9m4sTKy1qdtaZQjhM',
                'object'              => 'price',
                'active'              => true,
                'billing_scheme'      => 'per_unit',
                'created'             => 1647440806,
                'currency'            => 'usd',
                'livemode'            => false,
                'lookup_key'          => null,
                'metadata'            => [
                ],
                'nickname'            => 'default',
                'product'             => 'prod_KkKCKsfkSYPhcj',
                'recurring'           => [
                    'aggregate_usage' => null,
                    'interval'        => 'month',
                    'interval_count'  => 1,
                    'usage_type'      => 'licensed',
                ],
                'tax_behavior'        => 'unspecified',
                'tiers_mode'          => null,
                'transform_quantity'  => null,
                'type'                => 'recurring',
                'unit_amount'         => 55,
                'unit_amount_decimal' => '55',
            ]),
        ]);
    }
}
