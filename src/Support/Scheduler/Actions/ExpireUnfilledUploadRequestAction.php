<?php
namespace Support\Scheduler\Actions;

use Carbon\Carbon;
use Domain\UploadRequest\Models\UploadRequest;

class ExpireUnfilledUploadRequestAction
{
    public function __invoke()
    {
        UploadRequest::whereIn('status', ['active', 'filling'])
            ->cursor()
            ->each(function ($uploadRequest) {
                // Get timestamp of last upload if exist
                $isLastUpload = cache()->has("auto-filling.$uploadRequest->id");

                // Set as filled 3 hours after last upload
                if ($isLastUpload && Carbon::parse(cache()->get("auto-filling.$uploadRequest->id"))->diffInHours(now()) >= 3) {
                    $uploadRequest->update(['status' => 'filled']);
                }

                // If upload request exist more than 72 hours, then expire it
                if (! $isLastUpload && $uploadRequest->created_at->diffInHours(now()) >= 72) {
                    $uploadRequest->update(['status' => 'expired']);
                }
            });
    }
}
