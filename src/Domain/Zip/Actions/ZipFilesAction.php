<?php
namespace Domain\Zip\Actions;

use STS\ZipStream\ZipStream;
use Domain\Sharing\Models\Share;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use STS\ZipStream\ZipStreamFacade as Zip;

class ZipFilesAction
{
    /**
     * Zip selected files, and download it as response stream
     */
    public function __invoke(
        File | Collection $files,
        ?Share $shared = null,
    ): ZipStream {
        // Create zip
        $zip = Zip::create('files.zip');

        $files->map(function ($file) use ($zip) {
            // get file path
            $filePath = "files/$file->user_id/$file->basename";

            // Add file into zip
            if (Storage::exists($filePath)) {
                // local disk
                if (is_storage_driver('local')) {
                    $zip->add(Storage::path($filePath), $file->name);
                }

                // s3 client
                if (is_storage_driver('s3')) {
                    $bucketName = config('filesystems.disks.s3.bucket');

                    $zip->add("s3://$bucketName/$filePath", $file->name);
                }
            }
        });

        return $zip;
    }
}
