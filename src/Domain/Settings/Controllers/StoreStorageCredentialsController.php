<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\Response;
use Domain\Settings\Requests\StoreStorageCredentialsRequest;

class StoreStorageCredentialsController
{
    /**
     * Set new email credentials to .env file
     */
    public function __invoke(StoreStorageCredentialsRequest $request): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
            $drivers = [
                'local' => [
                    'FILESYSTEM_DISK' => 'local',
                ],
                's3'    => [
                    'FILESYSTEM_DISK'      => 's3',
                    'S3_ACCESS_KEY_ID'     => $request->input('storage.key') ?? null,
                    'S3_SECRET_ACCESS_KEY' => $request->input('storage.secret') ?? null,
                    'S3_DEFAULT_REGION'    => $request->input('storage.region') ?? null,
                    'S3_BUCKET'            => $request->input('storage.bucket') ?? null,
                    'S3_URL'               => $request->input('storage.endpoint') ?? null,
                ],
            ];

            // Get storage driver from request
            $driver = 'local' === $request->input('storage.driver') ? 'local' : 's3';

            // Storage credentials for storage
            setEnvironmentValue(
                $drivers[$driver]
            );

            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
