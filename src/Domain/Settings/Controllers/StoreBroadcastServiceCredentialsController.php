<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\Response;
use Domain\Settings\Requests\StoreBroadcastServiceCredentialsRequest;

class StoreBroadcastServiceCredentialsController
{
    /**
     * Configure stripe additionally
     */
    public function __invoke(StoreBroadcastServiceCredentialsRequest $request): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        // Get and store credentials
        if (! app()->runningUnitTests()) {
            $credentials = [
                'pusher' => [
                    'BROADCAST_DRIVER'   => 'pusher',
                    'PUSHER_APP_ID'      => $request->input('id'),
                    'PUSHER_APP_KEY'     => $request->input('key'),
                    'PUSHER_APP_SECRET'  => $request->input('secret'),
                    'PUSHER_APP_CLUSTER' => $request->input('cluster'),
                    'PUSHER_APP_HOST'    => '',
                    'PUSHER_APP_PORT'    => '',
                ],
                'native' => [
                    'BROADCAST_DRIVER'   => 'pusher',
                    'PUSHER_APP_ID'      => 'local',
                    'PUSHER_APP_KEY'     => 'local',
                    'PUSHER_APP_SECRET'  => 'local',
                    'PUSHER_APP_CLUSTER' => 'local',
                    'PUSHER_APP_HOST'    => $request->input('host'),
                    'PUSHER_APP_PORT'    => $request->input('port'),
                ],
                'none'   => [
                    'BROADCAST_DRIVER' => 'null',
                ],
            ];

            // Store credentials into the .env file
            setEnvironmentValue($credentials[$request->input('driver')]);

            // Clear cache
            if (! is_dev()) {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('config:cache');
            }
        }

        return response('Done', 204);
    }
}
