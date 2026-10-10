<?php
namespace Tests\Mocking\PayPal;

use Illuminate\Support\Facades\Http;

class VerifyWebhookPayPalMocksClass
{
    public function __invoke()
    {
        Http::fake([
            'https://api-m.sandbox.paypal.com/v1/oauth2/token'                           => Http::response([
                'scope'        => 'scope',
                'access_token' => 'jnjleqngtlq3l34jn6l2346n2l4',
                'token_type'   => 'Bearer',
                'app_id'       => 'APP-80W284485P519543T',
                'expires_in'   => 31349,
                'nonce'        => 'nonce',
            ], 204),
            'https://api-m.sandbox.paypal.com/v1/notifications/verify-webhook-signature' => Http::response([
                'verification_status' => 'SUCCESS',
            ]),
        ]);
    }
}
