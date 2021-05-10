<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Zip;
use App\Models\Share;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SchedulerService
{
    /**
     * Delete old zips
     */
    public function delete_old_zips(): void
    {
        Zip::where('created_at', '<=', now()->subDay()->toDateTimeString())
            ->get()
            ->each(function ($zip) {
                // Delete zip file
                Storage::disk('local')->delete("zip/$zip->basename");

                // Delete zip record
                $zip->delete();
            });
    }

    /**
     * Get and delete expired shared links
     */
    public function delete_expired_shared_links(): void
    {
        Share::whereNotNull('expire_in')
            ->get()
            ->each(function ($share) {
                // Get dates
                $created_at = Carbon::parse($share->created_at);

                // If time was over, then delete share record
                if ($created_at->diffInHours(now()) >= $share->expire_in) {
                    $share->delete();
                }
            });
    }

    /**
     * Get and delete failed files older than 24 hours
     */
    public function delete_failed_files(): void
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
