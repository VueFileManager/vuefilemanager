<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use Domain\Settings\Actions\GetBroadcastEnvironmentValuesAction;
use Domain\Settings\Requests\StoreBroadcastServiceCredentialsRequest;

class StoreBroadcastServiceCredentialsController
{
    /**
     * Configure stripe additionally
     */
    public function __invoke(
        StoreBroadcastServiceCredentialsRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The 3rd part authentication credentials was successfully set',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        // Get and store credentials
        if (! app()->runningUnitTests()) {
            $credentials = (new GetBroadcastEnvironmentValuesAction)($request->input('driver'), [
                'id'      => $request->input('id'),
                'key'     => $request->input('key'),
                'secret'  => $request->input('secret'),
                'cluster' => $request->input('cluster'),
                'host'    => $request->input('host'),
                'port'    => $request->input('port'),
                'tls'     => $request->boolean('tls'),
            ]);

            // Store credentials into the .env file
            setEnvironmentValue($credentials);

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
