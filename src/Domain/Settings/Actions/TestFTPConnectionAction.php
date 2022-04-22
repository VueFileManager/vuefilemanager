<?php
namespace Domain\Settings\Actions;

use Storage;
use ErrorException;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\Ftp\UnableToAuthenticate;

class TestFTPConnectionAction
{
    public function __invoke(array $credentials)
    {
        try {
            // Set temporary ftp connection
            config([
                'filesystems.disks.ftp' => [
                    'driver'   => 'ftp',
                    'host'     => $credentials['host'],
                    'username' => $credentials['user'],
                    'password' => $credentials['password'],
                ],
            ]);

            // Try to get files
            Storage::disk('ftp')->allFiles();

            // Try to create folder
            Storage::disk('ftp')->makeDirectory('ftp-test');

            // Delete test folder
            Storage::disk('ftp')->deleteDirectory('ftp-test');
        } catch (ErrorException | UnableToWriteFile | UnableToAuthenticate $error) {
            abort(
                response()->json([
                    'type'    => 'ftp-connection-error',
                    'title'   => 'FTP Connection Error',
                    'message' => $error->getMessage(),
                ], 401)
            );
        }
    }
}
