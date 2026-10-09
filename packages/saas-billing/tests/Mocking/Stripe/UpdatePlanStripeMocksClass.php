<?php
namespace Tests\Mocking\Stripe;

use Illuminate\Support\Facades\Http;

class UpdatePlanStripeMocksClass
{
    public function __invoke($plan)
    {
        return Http::fake([
            'https://api.stripe.com/v1/products/*' => Http::response([
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
        ]);
    }
}
