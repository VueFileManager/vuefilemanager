<?php
namespace Domain\Files\Actions;

use Aws\S3\MultipartUploader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Aws\Exception\MultipartUploadException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MoveFileToExternalStorageAction
{
    /**
     * Move file to external storage if is set
     */
    public function __invoke(
        string $file,
        string $user_id
    ): void {
        $disk_local = Storage::disk('local');

        // Get file size
        $filesize = $disk_local->size("files/$user_id/$file");

        // If file is bigger than 5.2 MB then run multipart upload
        if ($filesize > 5242880) {
            // Get client
            $client = Storage::disk('s3')->getClient();

            // Prepare the upload parameters.
            // TODO: replace local files with temp folder
            $uploader = new MultipartUploader($client, config('filesystems.disks.local.root') . "/files/$user_id/$file", [
                'bucket' => config('filesystems.disks.s3.bucket'),
                'key'    => "files/$user_id/$file",
            ]);

            try {
                // Upload content
                $uploader->upload();
            } catch (MultipartUploadException $e) {
                // Write error log
                Log::error($e->getMessage());

                // Delete file after error
                $disk_local->delete("files/$user_id/$file");

                throw new HttpException(409, $e->getMessage());
            }
        } else {
            // Stream file object to s3
            // TODO: replace local files with temp folder
            Storage::putFileAs("files/$user_id", config('filesystems.disks.local.root') . "/files/$user_id/$file", $file, 'private');
        }

        // Delete file after upload
        $disk_local->delete("files/$user_id/$file");
    }
}
