<?php
namespace Tests\Mocking\PayStack;

use Illuminate\Support\Facades\Http;

class CreatePlanPaystackMocksClass
{
    public function __invoke($plan)
    {
        return Http::fake([
            'https://api.paystack.co/plan'                          => Http::response([
                'status'  => true,
                'message' => 'Plan created',
                'data'    => [
                    'name'          => $plan->name,
                    'amount'        => $plan->amount * 100,
                    'interval'      => $plan->interval,
                    'integration'   => 100032,
                    'domain'        => 'test',
                    'plan_code'     => 'PLN_gx2wn530m0i3w3m',
                    'send_invoices' => true,
                    'send_sms'      => true,
                    'hosted_page'   => false,
                    'currency'      => 'ZAR',
                    'id'            => 28,
                    'createdAt'     => '2016-03-29T22:42:50.811Z',
                    'updatedAt'     => '2016-03-29T22:42:50.811Z',
                ],
            ]),
        ]);
    }
}
