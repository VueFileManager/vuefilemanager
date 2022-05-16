<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use Domain\Settings\Models\Setting;
use Domain\Settings\Actions\TestPayPalConnectionAction;
use Domain\Settings\Actions\TestStripeConnectionAction;
use Domain\Settings\Actions\TestPaystackConnectionAction;
use Domain\Settings\Requests\StorePaymentServiceCredentialsRequest;

class StorePaymentServiceCredentialsController
{
    public function __construct(
        public TestPaystackConnectionAction $testPaystackConnection,
        public TestStripeConnectionAction $testStripeConnection,
        public TestPayPalConnectionAction $testPayPalConnection,
    ) {
    }

    /**
     * Configure stripe additionally
     */
    public function __invoke(
        StorePaymentServiceCredentialsRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The payment credentials was successfully set',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        $options = [
            'stripe'   => [
                'name'  => 'allowed_stripe',
                'value' => 1,
            ],
            'paypal'   => [
                'name'  => 'allowed_paypal',
                'value' => 1,
            ],
            'paystack' => [
                'name'  => 'allowed_paystack',
                'value' => 1,
            ],
        ];

        // Get options
        collect([$options[$request->input('service')]])
            ->each(fn ($setting) => Setting::updateOrCreate([
                'name' => $setting['name'],
            ], [
                'value' => $setting['value'],
            ]));

        $PayPalDefaultMode = config('subscription.credentials.paypal.is_live') ? 'true' : 'false';

        // Get and store credentials
        if (! app()->runningUnitTests()) {
            // Test payment gateway connection
            match ($request->input('service')) {
                'paystack' => ($this->testPaystackConnection)([
                    'key'    => $request->input('key'),
                    'secret' => $request->input('secret'),
                ]),
                'stripe' => ($this->testStripeConnection)([
                    'key'     => $request->input('key'),
                    'secret'  => $request->input('secret'),
                    'webhook' => $request->input('webhook'),
                ]),
                'paypal' => ($this->testPayPalConnection)([
                    'key'     => $request->input('key'),
                    'secret'  => $request->input('secret'),
                    'webhook' => $request->input('webhook'),
                    'live'    => $request->has('live') ? (string) $request->input('live') : $PayPalDefaultMode,
                ]),
                default => null
            };

            $credentials = [
                'stripe'   => [
                    'STRIPE_PUBLIC_KEY'     => $request->input('key'),
                    'STRIPE_SECRET_KEY'     => $request->input('secret'),
                    'STRIPE_WEBHOOK_SECRET' => $request->input('webhook'),
                ],
                'paystack' => [
                    'PAYSTACK_PUBLIC_KEY' => $request->input('key'),
                    'PAYSTACK_SECRET'     => $request->input('secret'),
                ],
                'paypal'   => [
                    'PAYPAL_CLIENT_ID'     => $request->input('key'),
                    'PAYPAL_CLIENT_SECRET' => $request->input('secret'),
                    'PAYPAL_WEBHOOK_ID'    => $request->input('webhook'),
                    'PAYPAL_IS_LIVE'       => $request->has('live') ? (string) $request->input('live') : $PayPalDefaultMode,
                ],
            ];

            // Store credentials into the .env file
            setEnvironmentValue($credentials[$request->input('service')]);

            // Call plan synchronization for makingcg/subscription package
            cache()->add('action.synchronize-plans', now()->toString());

            // Clear cache
            if (! is_dev()) {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('config:cache');
            }
        }

        return response()->json($message);
    }
}
