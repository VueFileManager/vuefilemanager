<?php
namespace Support\Scheduler\Actions;

use Domain\Zip\Models\Zip;
use Illuminate\Support\Facades\Storage;

class DeleteOldZipsAction
{
    /**
     * Delete old zips
     */
    public function __invoke(): void
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
}
