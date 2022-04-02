<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\Response;
use Aws\S3\Exception\S3Exception;
use League\Flysystem\UnableToWriteFile;
use Domain\Settings\DTO\S3CredentialsData;
use Domain\Settings\Actions\TestS3ConnectionAction;
use Domain\Settings\Requests\StoreStorageCredentialsRequest;

class StoreStorageCredentialsController
{
    public function __construct(
        private TestS3ConnectionAction $testS3Connection,
    ) {
    }

    /**
     * Set new email credentials to .env file
     */
    public function __invoke(StoreStorageCredentialsRequest $request): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if (! app()->runningUnitTests()) {
            // Test s3 credentials
            if ($request->input('storage.driver') !== 'local') {
                try {
                    // connect to the s3
                    ($this->testS3Connection)(S3CredentialsData::fromRequest($request));
                } catch (S3Exception | UnableToWriteFile $error) {
                    return response([
                        'type'    => 's3-connection-error',
                        'title'   => 'S3 Connection Error',
                        'message' => $error->getMessage(),
                    ], 401);
                }
            }

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
            setEnvironmentValue($drivers[$driver]);

            // Cache the config
            Artisan::call('config:cache');
        }

        return response('Done', 204);
    }
}
