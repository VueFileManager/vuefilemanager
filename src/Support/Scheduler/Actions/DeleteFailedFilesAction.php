<?php
namespace Support\Scheduler\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteFailedFilesAction
{
    /**
     * Get and delete failed files older than 24 hours
     */
    public function __invoke(): void
    {
        $local_disk = Storage::disk('local');

        // Get all files from storage
        $files = collect([
            //$local_disk->allFiles('files'),
            $local_disk->allFiles('chunks'),
        ])->collapse();

        $files->each(function ($file) use ($local_disk) {
            // Get the file's last modification time.
            $last_modified = $local_disk
                ->lastModified($file);

            // Get diffInHours
            $diff = Carbon::parse($last_modified)
                ->diffInHours(now());

            // Delete if file is in local storage more than 24 hours
            if ($diff >= 24) {
                Log::info("Failed file or chunk $file deleted.");

                // Delete file from local storage
                $local_disk->delete($file);
            }
        });
    }
}
