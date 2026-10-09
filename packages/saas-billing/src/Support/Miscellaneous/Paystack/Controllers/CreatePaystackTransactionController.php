<?php
namespace VueFileManager\Subscription\Support\Miscellaneous\Paystack\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use VueFileManager\Subscription\Domain\Plans\Models\PlanDriver;
use VueFileManager\Subscription\Support\Services\PayStackHttpClient;
use VueFileManager\Subscription\Support\Miscellaneous\Paystack\Requests\CreatePaystackTransactionRequest;

class CreatePaystackTransactionController
{
    use PayStackHttpClient;

    public function __invoke(
        CreatePaystackTransactionRequest $request
    ): JsonResponse {
        $user = Auth::user();

        // Get gateway plan id
        $amount = $request->has('planCode')
            ? PlanDriver::where('driver_plan_id', $request->input('planCode'))->first()->amount * 100
            : $request->input('amount');

        $response = $this->post('/transaction/initialize', [
            'amount'       => $amount,
            'email'        => $user->email,
            'callback_url' => url('/user/settings/billing'),
            'plan'         => $request->input('planCode') ?? null,
            'channels'     => ['card', 'bank', 'ussd', 'qr', 'mobile_money', 'bank_transfer'],
        ]);

        return response()->json([
            'type'    => 'success',
            'message' => 'Checkout session was created successfully',
            'data'    => [
                'authorization_url' => $response->json()['data']['authorization_url'],
            ],
        ]);
    }
}
