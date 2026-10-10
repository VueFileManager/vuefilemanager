<?php
namespace Tests\Mocking\PayStack;

use Illuminate\Support\Facades\Http;

class DeletePlanPaystackMocksClass
{
    public function __invoke($plan)
    {
        return Http::fake([
            'https://api.paystack.co/plan/*' => Http::response([
                'status'  => true,
                'message' => 'Plan Deleted',
                'data'    => [
                    'name'      => $plan->name,
                    'createdAt' => '2016-03-29T22:42:50.811Z',
                    'updatedAt' => '2016-03-29T22:42:50.811Z',
                ],
            ]),
        ]);
    }
}
