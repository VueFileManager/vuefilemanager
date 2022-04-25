<?php
namespace Domain\Settings\Actions;

use Storage;
use Aws\S3\Exception\S3Exception;
use League\Flysystem\UnableToWriteFile;
use Domain\Settings\DTO\S3CredentialsData;

class TestS3ConnectionAction
{
    public function __invoke(S3CredentialsData $credentials): void
    {
        try {
            // Set temporary s3 connection
            config([
                'filesystems.disks.s3' => [
                    'driver'     => 's3',
                    'key'        => $credentials->key,
                    'secret'     => $credentials->secret,
                    'region'     => $credentials->region,
                    'bucket'     => $credentials->bucket,
                    'endpoint'   => $credentials->endpoint,
                    'visibility' => 'private',
                ],
            ]);

            // Try to get files
            Storage::disk('s3')->allFiles();

            // Try to create folder
            Storage::disk('s3')->makeDirectory('s3-test');

            // Delete test folder
            Storage::disk('s3')->deleteDirectory('s3-test');
        } catch (S3Exception | UnableToWriteFile $error) {
            abort(
                response()->json([
                    'type'    => 's3-connection-error',
                    'title'   => 'S3 Connection Error - Wrong Credentials or Not Permitted',
                    'message' => $error->getMessage(),
                ], 401)
            );
        }
    }
}
