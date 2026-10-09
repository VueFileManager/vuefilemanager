<?php
namespace Domain\Zip\Actions;

use Gate;
use Illuminate\Support\Str;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\StreamedResponse;
use STS\ZipStream\Builder;
use STS\ZipStream\Facades\Zip;

class ZipAction
{
    public function __invoke(
        Collection $folders,
        Collection $files,
        ?Share $shared = null
    ): Builder {
        // Get zip name from single requested folder
        if ($files->isEmpty() && $folders->count() === 1) {
            $zipName = Str::slug($folders->first()->name) . '.zip';
        }

        // Create zip
        $zip = Zip::create($zipName ?? 'files.zip');

        // Zip Files
        $files->map(function ($file) use ($zip, $shared) {
            // Check user privileges to the file
            if (! Gate::any(['can-edit', 'can-view'], [$file, $shared])) {
                abort(403, 'Access Denied');
            }

            // get file path
            $filePath = "files/$file->user_id/$file->basename";

            // Add file into zip
            if (Storage::exists($filePath)) {
                // local disk
                if (isStorageDriver('local')) {
                    $zip->add(Storage::path($filePath), $file->name);
                }

                // s3 client
                if (isStorageDriver('s3')) {
                    $bucketName = config('filesystems.disks.s3.bucket');

                    $zip->add("s3://$bucketName/$filePath", $file->name);
                }

                // ftp client
                if (isStorageDriver('ftp')) {
                    $zip->addRaw(Storage::get($filePath), $file->name);
                }
            }
        });

        // Zip Folders
        $folders->map(function ($folder) use ($zip, $shared) {
            // Check user privileges to the folder
            if (! Gate::any(['can-edit', 'can-view'], [$folder, $shared])) {
                abort(403, 'Access Denied');
            }

            // Get folder
            $requested_folder = Folder::with(['folders.files', 'files'])
                ->where('id', $folder->id)
                ->with('folders')
                ->first();

            $folderFiles = get_files_for_zip($requested_folder, collect([]));

            foreach ($folderFiles as $file) {
                // get file path
                $filePath = "files/{$file['user_id']}/{$file['basename']}";

                // Add file into zip
                if (Storage::exists($filePath)) {
                    $zipDestination = "{$file['folder_path']}/{$file['name']}";

                    // local disk
                    if (isStorageDriver('local')) {
                        $zip->add(Storage::path($filePath), $zipDestination);
                    }

                    if (isStorageDriver('s3')) {
                        $bucketName = config('filesystems.disks.s3.bucket');

                        $zip->add("s3://$bucketName/$filePath", $zipDestination);
                    }

                    if (isStorageDriver('ftp')) {
                        $zip->addRaw(Storage::get($filePath), $zipDestination);
                    }
                }
            }
        });

        return $zip;
    }

    /**
     * Create streamed download response for the zip with explicit download headers
     */
    public static function toResponse(Builder $zip): StreamedResponse
    {
        $response = $zip->response();

        $response->headers->set('Content-Type', 'application/x-zip');
        $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $zip->getOutputName(),
            Str::ascii($zip->getOutputName()) ?: 'files.zip',
        ));

        return $response;
    }
}
