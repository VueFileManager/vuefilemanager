<?php
namespace Domain\UploadRequest\Controllers;

use Illuminate\Http\Response;
use Domain\UploadRequest\Models\UploadRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Domain\UploadRequest\Resources\UploadRequestResource;
use Domain\UploadRequest\Notifications\UploadRequestFulfilledNotification;

class SetUploadRequestAsFilledController
{
    public function __invoke(UploadRequest $uploadRequest): Response|Application|ResponseFactory
    {
        $uploadRequest->update([
            'status' => 'filled',
        ]);

        // Send user notification
        if (! (is_demo() && $uploadRequest->user->email === 'howdy@hi5ve.digital')) {
            $uploadRequest->user->notify(new UploadRequestFulfilledNotification($uploadRequest));
        }

        return response(new UploadRequestResource($uploadRequest), 201);
    }
}
