<?php
namespace Domain\Settings\Controllers;

use Artisan;
use Illuminate\Http\JsonResponse;
use Domain\Settings\DTO\S3CredentialsData;
use Domain\Settings\Actions\TestS3ConnectionAction;
use Domain\Settings\Actions\TestFTPConnectionAction;
use Domain\Settings\Requests\StoreStorageCredentialsRequest;

class StoreStorageCredentialsController
{
    public function __construct(
        private TestFTPConnectionAction $testFTPConnection,
        private TestS3ConnectionAction $testS3Connection,
    ) {
    }

    /**
     * Set new email credentials to .env file
     */
    public function __invoke(
        StoreStorageCredentialsRequest $request
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The storage credentials was successfully set',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        // Get storage driver from request
        $driver = match ($request->input('storage.driver')) {
            's3', 'storj', 'spaces', 'wasabi', 'backblaze', 'oss', 'other' => 's3',
            'local' => 'local',
            'ftp'   => 'ftp',
        };

        if (! app()->runningUnitTests()) {
            // Test driver connection
            match ($driver) {
                's3' => ($this->testS3Connection)(
                    S3CredentialsData::fromRequest($request)
                ),
                'ftp' => ($this->testFTPConnection)([
                    'host'     => $request->input('storage.ftp.host'),
                    'user'     => $request->input('storage.ftp.user'),
                    'password' => $request->input('storage.ftp.password'),
                ]),
                default => null
            };

            $drivers = [
                'local' => [
                    'FILESYSTEM_DISK' => 'local',
                ],
                's3'    => [
                    'FILESYSTEM_DISK'      => 's3',
                    'S3_ACCESS_KEY_ID'     => $request->input('storage.s3.key') ?? null,
                    'S3_SECRET_ACCESS_KEY' => $request->input('storage.s3.secret') ?? null,
                    'S3_DEFAULT_REGION'    => $request->input('storage.s3.region') ?? null,
                    'S3_BUCKET'            => $request->input('storage.s3.bucket') ?? null,
                    'S3_URL'               => $request->input('storage.s3.endpoint') ?? null,
                ],
                'ftp' => [
                    'FILESYSTEM_DISK' => 'ftp',
                    'FTP_HOST'        => $request->input('storage.ftp.host') ?? null,
                    'FTP_USERNAME'    => $request->input('storage.ftp.user') ?? null,
                    'FTP_PASSWORD'    => $request->input('storage.ftp.password') ?? null,
                ],
            ];

            // Storage credentials for storage
            setEnvironmentValue($drivers[$driver]);

            // Cache the config
            Artisan::call('config:cache');
        }

        return response()->json($message);
    }
}
