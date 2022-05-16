<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use Domain\Settings\Models\Setting;
use Domain\Settings\Requests\StoreSocialServiceCredentialsRequest;

class StoreSocialServiceCredentialsController
{
    /**
     * Configure stripe additionally
     */
    public function __invoke(
        StoreSocialServiceCredentialsRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The 3rd part authentication credentials was successfully set',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        // Set on social login
        Setting::updateOrCreate([
            'name' => "allowed_{$request->input('service')}",
        ], [
            'value' => 1,
        ]);

        // Get and store credentials
        if (! app()->runningUnitTests()) {
            $credentials = [
                'facebook'   => [
                    'FACEBOOK_CLIENT_ID'     => $request->input('client_id'),
                    'FACEBOOK_CLIENT_SECRET' => $request->input('client_secret'),
                ],
                'google' => [
                    'GOOGLE_CLIENT_ID'         => $request->input('client_id'),
                    'GOOGLE_CLIENT_SECRET'     => $request->input('client_secret'),
                ],
                'github'   => [
                    'GITHUB_CLIENT_ID'     => $request->input('client_id'),
                    'GITHUB_CLIENT_SECRET' => $request->input('client_secret'),
                ],
                'recaptcha'   => [
                    'RECAPTCHA_CLIENT_ID'      => $request->input('client_id'),
                    'RECAPTCHA_CLIENT_SECRET'  => $request->input('client_secret'),
                ],
            ];

            // Store credentials into the .env file
            setEnvironmentValue($credentials[$request->input('service')]);

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
