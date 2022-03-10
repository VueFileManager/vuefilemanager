<?php
namespace Domain\UploadRequest\Controllers;

use Auth;
use Notification;
use App\Http\Controllers\Controller;
use Domain\UploadRequest\Requests\StoreUploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;
use Domain\UploadRequest\Notifications\UploadRequestNotification;

class CreateUploadRequestController extends Controller
{
    public function __invoke(StoreUploadRequest $request)
    {
        $uploadRequest = Auth::user()->uploadRequest()->create([
            'folder_id' => $request->input('folder_id'),
            'email'     => $request->input('email'),
            'notes'     => $request->input('notes'),
            'name'      => $request->input('name'),
        ]);

        // If user type email, notify by email
        if ($uploadRequest->email) {
            Notification::route('mail', $uploadRequest->email)
                ->notify(new UploadRequestNotification($uploadRequest));
        }

        return response(new UploadRequestResource($uploadRequest), 201);
    }
}
