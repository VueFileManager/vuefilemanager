<?php
namespace Tests\Mocking\Stripe;

use Illuminate\Support\Facades\Http;

class CreatePlanStripeMocksClass
{
    public function __invoke($plan)
    {
        return Http::fake([
            'https://api.stripe.com/v1/products'                    => Http::response([
                'id'                   => 'prod_KhIRa4HjCgFaNJ',
                'object'               => 'product',
                'active'               => true,
                'created'              => 1638369119,
                'description'          => $plan->description,
                'images'               => [],
                'livemode'             => false,
                'metadata'             => [],
                'name'                 => $plan->name,
                'package_dimensions'   => null,
                'shippable'            => null,
                'statement_descriptor' => null,
                'tax_code'             => null,
                'unit_label'           => null,
                'updated'              => 1638369119,
                'url'                  => null,
            ]),
            'https://api.stripe.com/v1/prices'                      => Http::response([
                'id'                  => 'price_1K1wJPB9m4sTKy1qcHaLkXki',
                'object'              => 'price',
                'active'              => true,
                'billing_scheme'      => 'per_unit',
                'created'             => 1638378583,
                'currency'            => 'usd',
                'livemode'            => false,
                'lookup_key'          => null,
                'metadata'            => [
                ],
                'nickname'            => null,
                'product'             => 'prod_KhIRa4HjCgFaNJ',
                'recurring'           => [
                    'aggregate_usage' => null,
                    'interval'        => $plan->interval,
                    'interval_count'  => 1,
                    'usage_type'      => 'licensed',
                ],
                'tax_behavior'        => 'unspecified',
                'tiers_mode'          => null,
                'transform_quantity'  => null,
                'type'                => 'recurring',
                'unit_amount'         => $plan->amount * 100,
                'unit_amount_decimal' => $plan->amount * 100,
            ]),
        ]);
    }
}
