<?php
namespace Tests\Mocking\PayPal;

use Illuminate\Support\Facades\Http;

class DeletePlanPayPalMocksClass
{
    public function __invoke($plan)
    {
        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token'               => Http::response([
                'scope'        => 'scope',
                'access_token' => 'jnjleqngtlq3l34jn6l2346n2l4',
                'token_type'   => 'Bearer',
                'app_id'       => 'APP-80W284485P519543T',
                'expires_in'   => 31349,
                'nonce'        => 'nonce',
            ]),
            'https://api-m.sandbox.paypal.com/v1/billing/plans/*/deactivate' => Http::response([], 204),
        ]);
    }
}
