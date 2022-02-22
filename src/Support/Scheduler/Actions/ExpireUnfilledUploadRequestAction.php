<?php
namespace Support\Scheduler\Actions;

use Carbon\Carbon;
use Domain\UploadRequest\Models\UploadRequest;

class ExpireUnfilledUploadRequestAction
{
    public function __invoke()
    {
        UploadRequest::where('status', 'active')
            ->cursor()
            ->each(function ($uploadRequest) {
                // Get dates
                $created_at = Carbon::parse($uploadRequest->created_at);

                // If time was over, then expire record
                if ($created_at->diffInHours(now()) >= 72) {
                    $uploadRequest->update([
                        'status' => 'expired',
                    ]);
                }
            });
    }
}
