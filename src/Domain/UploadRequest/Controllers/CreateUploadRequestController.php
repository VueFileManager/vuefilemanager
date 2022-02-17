<?php

namespace Domain\UploadRequest\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Domain\UploadRequest\Notifications\UploadRequestNotification;
use Domain\UploadRequest\Requests\StoreUploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;
use Notification;

class CreateUploadRequestController extends Controller
{
    public function __invoke(StoreUploadRequest $request)
    {
        $uploadRequest = Auth::user()->uploadRequest()->create([
            'folder_id' => $request->input('folder_id'),
            'email'     => $request->input('email'),
            'notes'     => $request->input('notes'),
        ]);

        // If user type email, notify by email
        if ($uploadRequest->email) {
            Notification::route('mail', $uploadRequest->email)
                ->notify(new UploadRequestNotification($uploadRequest));
        }

        return response(new UploadRequestResource($uploadRequest), 201);
    }
}