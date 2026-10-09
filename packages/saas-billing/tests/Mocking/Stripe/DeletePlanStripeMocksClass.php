<?php
namespace Tests\Mocking\Stripe;

use Illuminate\Support\Facades\Http;

class DeletePlanStripeMocksClass
{
    public function __invoke($plan)
    {
        return Http::fake([
            'https://api.stripe.com/v1/plans/*' => Http::response([
                'id'      => 'price_HKL7vHEYRSC4Ur',
                'object'  => 'plan',
                'deleted' => true,
            ]),
            'https://api.stripe.com/v1/products/*' => Http::response([
                'id'      => 'prod_HKL7vHEYRSC4Ur',
                'object'  => 'product',
                'deleted' => true,
            ]),
        ]);
    }
}
