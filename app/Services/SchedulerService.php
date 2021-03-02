<?php


namespace App\Services;


use App\Models\Share;
use App\Models\Zip;
use Carbon\Carbon;

class SchedulerService
{
    /**
     * Delete old zips
     */
    public function delete_old_zips(): void
    {
        Zip::where('created_at', '<=', Carbon::now()->subDay()->toDateTimeString())
            ->get()
            ->each(function ($zip) {

                // Delete zip file
                \Storage::disk('local')->delete("zip/$zip->basename");

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
                if ($created_at->diffInHours(Carbon::now()) >= $share->expire_in) {
                    $share->delete();
                }
            });
    }
}