<?php
namespace Domain\Zipping\Actions;

use Illuminate\Support\Str;
use Domain\Zipping\Models\Zip;
use Domain\Sharing\Models\Share;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ZipFilesAction
{
    /**
     * Zip selected files, store it in /zip folder and retrieve zip record
     */
    public function __invoke(
        File | Collection $files,
        ?Share $shared = null,
    ): Zip {
        // Local storage instance
        $disk_local = Storage::disk('local');

        // Move file to local storage from external storage service
        if (! is_storage_driver('local')) {
            $files->each(function ($file) use ($disk_local) {
                try {
                    $disk_local->put("temp/$file->basename", Storage::get("files/$file->user_id/$file->basename"));
                } catch (FileNotFoundException $e) {
                    throw new HttpException(404, 'File not found');
                }
            });
        }

        // Get zip path
        $zip_name = Str::random() . '.zip';

        // Create zip
        $zipper = new \Madnest\Madzipper\Madzipper;
        $zip = $zipper->make($disk_local->path("zip/$zip_name"));

        // Add files to zip
        $files->each(function ($file) use ($zip, $disk_local) {
            $file_path = is_storage_driver('local')
                ? $disk_local->path("files/$file->user_id/$file->basename")
                : $disk_local->path("temp/$file->basename");

            $zip->addString("$file->name.$file->mimetype", File::get($file_path));
        });

        // Close zip
        //$zip->close();

        // Delete temporary files
        if (! is_storage_driver('local')) {
            $files->each(function ($file) use ($disk_local) {
                $disk_local->delete("temp/$file->basename");
            });
        }

        // Store zip record
        return Zip::create([
            'user_id'      => $shared->user_id ?? Auth::id(),
            'shared_token' => $shared->token ?? null,
            'basename'     => $zip_name,
        ]);
    }
}
