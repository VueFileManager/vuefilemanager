<?php
namespace Domain\Zip\Actions;

use STS\ZipStream\ZipStream;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use STS\ZipStream\ZipStreamFacade as Zip;

class ZipFolderAction
{
    /**
     * Zip requested folder
     */
    public function __invoke(
        $id,
        ?Share $shared = null
    ): ZipStream {
        $user_id = Auth::id() ?? $shared->user_id;

        // Get folder
        $requested_folder = Folder::with(['folders.files', 'files'])
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->with('folders')
            ->first();

        $files = get_files_for_zip($requested_folder, collect([]));

        // Create zip
        $zip = Zip::create('files.zip');

        foreach ($files as $file) {
            // get file path
            $filePath = "files/$user_id/{$file['basename']}";

            // Add file into zip
            if (Storage::exists($filePath)) {
                $zipDestination = "{$file['folder_path']}/{$file['name']}";

                // local disk
                if (is_storage_driver('local')) {
                    $zip->add(Storage::path($filePath), $zipDestination);
                }

                // s3 client
                if (is_storage_driver('s3')) {
                    $bucketName = config('filesystems.disks.s3.bucket');

                    $zip->add("s3://$bucketName/$filePath", $zipDestination);
                }
            }
        }

        return $zip;
    }
}
