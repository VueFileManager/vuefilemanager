<?php
namespace Tests\Mocking\PayStack;

use Illuminate\Support\Facades\Http;

class CreateCustomerPaystackMocksClass
{
    public function __invoke($user)
    {
        return Http::fake([
            'https://api.paystack.co/customer'    => Http::response([
                'status'  => true,
                'message' => 'Customer created',
                'data'    => [
                    'email'           => $user->email,
                    'integration'     => 100032,
                    'domain'          => 'test',
                    'customer_code'   => 'CUS_xnxdt6s1zg1f4nx',
                    'id'              => 1173,
                    'identified'      => false,
                    'identifications' => null,
                    'createdAt'       => '2016-03-29T20:03:09.584Z',
                    'updatedAt'       => '2016-03-29T20:03:09.584Z',
                ],
            ]),
        ]);
    }
}
