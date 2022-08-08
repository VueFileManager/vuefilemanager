<?php
namespace Domain\UploadRequest\Controllers;

use Gate;
use Notification;
use App\Users\Models\User;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Domain\UploadRequest\Requests\StoreUploadRequest;
use Domain\UploadRequest\Resources\UploadRequestResource;
use Domain\UploadRequest\Notifications\UploadRequestNotification;

class CreateUploadRequestController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function __invoke(
        StoreUploadRequest $request
    ): JsonResponse {
        // Check if user is owner of the file
        if ($request->has('folder_id')) {
            $folder = Folder::findOrFail($request->input('folder_id'));

            Gate::authorize('owner', [$folder]);
        }

        // Create upload request
        $uploadRequest = auth()->user()->uploadRequest()->create([
            'folder_id' => $request->input('folder_id'),
            'email'     => $request->input('email'),
            'notes'     => $request->input('notes'),
            'name'      => $request->input('name'),
        ]);

        // If user type email, notify by email
        if ($request->has('email')) {
            // Check if user exists
            $user = User::where('email', $uploadRequest->email)
                ->first();

            if ($user) {
                $user->notify(new UploadRequestNotification($uploadRequest));
            } else {
                // Get default app locale
                $appLocale = get_settings('language') ?? 'en';

                Notification::route('mail', $uploadRequest->email)
                    ->notify(
                        (new UploadRequestNotification($uploadRequest))->locale($appLocale)
                    );
            }
        }

        return response()->json(new UploadRequestResource($uploadRequest), 201);
    }
}
