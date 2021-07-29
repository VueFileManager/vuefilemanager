<?php


namespace Domain\Zip\Actions;


use Domain\Folders\Models\Folder;
use Domain\Sharing\Models\Share;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use STS\ZipStream\ZipStreamFacade as Zip;
use ZipStream\ZipStream;

class ZipAction
{
    public function __invoke(
        Collection $folders,
        Collection $files,
        ?Share $shared = null
    ): ZipStream {

        // Get user id
        $user_id = Auth::id() ?? $shared->user_id;

        // Create zip
        $zip = Zip::create('files.zip');

        // Zip Files
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

        // Zip Folders
        $folders->map(function ($folder) use ($zip, $user_id) {
            // Get folder
            $requested_folder = Folder::with(['folders.files', 'files'])
                ->where('id', $folder->id)
                ->where('user_id', $user_id)
                ->with('folders')
                ->first();

            $folderFiles = get_files_for_zip($requested_folder, collect([]));

            foreach ($folderFiles as $file) {
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
        });
        
        return $zip;
    }
}