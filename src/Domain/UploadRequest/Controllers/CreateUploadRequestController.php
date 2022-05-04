<?php
namespace Domain\UploadRequest\Controllers;

use Auth;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Notification;
use Illuminate\Http\JsonResponse;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\UploadRequest\Requests\StoreUploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;
use Domain\UploadRequest\Notifications\UploadRequestNotification;

class CreateUploadRequestController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(StoreUploadRequest $request): JsonResponse
    {
        // Check if user is owner of the file
        if ($request->has('folder_id')) {
            $folder = Folder::findOrFail($request->input('folder_id'));

            Gate::authorize('owner', [$folder]);
        }

        // Create upload request
        $uploadRequest = Auth::user()->uploadRequest()->create([
            'folder_id' => $request->input('folder_id'),
            'email'     => $request->input('email'),
            'notes'     => $request->input('notes'),
            'name'      => $request->input('name'),
        ]);

        // If user type email, notify by email
        if ($request->has('email')) {
            Notification::route('mail', $uploadRequest->email)
                ->notify(new UploadRequestNotification($uploadRequest));
        }

        return response()->json(new UploadRequestResource($uploadRequest), 201);
    }
}
