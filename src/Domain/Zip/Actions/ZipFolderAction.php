<?php
namespace Domain\Zip\Actions;

use Domain\Zip\Models\Zip;
use Illuminate\Support\Str;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ZipFolderAction
{
    /**
     * Zip requested folder
     */
    public function __invoke(
        $id,
        ?Share $shared = null
    ): Zip {
        // Get folder
        $requested_folder = Folder::with(['folders.files', 'files'])
            ->where('id', $id)
            ->where('user_id', Auth::id() ?? $shared->user_id)
            ->with('folders')
            ->first();

        $files = get_files_for_zip($requested_folder, collect([]));

        // Local storage instance
        $disk_local = Storage::disk('local');

        // Move file to local storage from external storage service
        if (! is_storage_driver('local')) {
            foreach ($files as $file) {
                try {
                    $disk_local->put("temp/{$file['basename']}", Storage::get("files/$requested_folder->user_id/{$file['basename']}"));
                } catch (FileNotFoundException $e) {
                    throw new HttpException(404, 'File not found');
                }
            }
        }

        // Get zip path
        $zip_name = Str::random(16) . '-' . Str::slug($requested_folder->name) . '.zip';

        // Create zip
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zip = $zipper->make($disk_local->path("zip/$zip_name"));

        // Add files to zip
        foreach ($files as $file) {
            $file_path = is_storage_driver('local')
                ? $disk_local->path("files/$requested_folder->user_id/{$file['basename']}")
                : $disk_local->path("temp/{$file['basename']}");

            $zip
                ->folder($file['folder_path'])
                ->addString("{$file['name']}.{$file['mimetype']}", File::get($file_path));
        }

        // Close zip
        //$zip->close();

        // Delete temporary files
        if (! is_storage_driver('local')) {
            foreach ($files as $file) {
                $disk_local->delete('temp/' . $file['basename']);
            }
        }

        // Store zip record
        return Zip::create([
            'user_id'      => $shared->user_id ?? Auth::id(),
            'shared_token' => $shared->token ?? null,
            'basename'     => $zip_name,
        ]);
    }
}
