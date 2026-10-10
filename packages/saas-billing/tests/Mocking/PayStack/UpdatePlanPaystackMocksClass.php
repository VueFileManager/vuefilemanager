<?php
namespace Tests\Mocking\PayStack;

use Illuminate\Support\Facades\Http;

class UpdatePlanPaystackMocksClass
{
    public function __invoke($plan)
    {
        return Http::fake([
            'https://api.paystack.co/plan/*'                      => Http::response([
                'status'  => true,
                'message' => 'Plan updated. 1 subscription(s) affected',
            ]),
        ]);
    }
}
