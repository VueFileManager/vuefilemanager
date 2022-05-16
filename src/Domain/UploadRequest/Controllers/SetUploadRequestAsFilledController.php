<?php
namespace Domain\UploadRequest\Controllers;

use Illuminate\Http\JsonResponse;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification;

class SetUploadRequestAsFilledController
{
    public function __invoke(
        UploadRequest $uploadRequest
    ): JsonResponse {
        $uploadRequest->update([
            'status' => 'filled',
        ]);

        // Send user notification
        if ($uploadRequest->user->email !== 'howdy@hi5ve.digital') {
            $uploadRequest->user->notify(new UploadRequestFulfilledNotification($uploadRequest));
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'File request was successfully set as filled',
        ]);
    }
}
