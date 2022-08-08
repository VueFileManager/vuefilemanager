<?php
namespace Domain\RemoteUpload\Controllers;

use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\RemoteUpload\Requests\RemoteUploadRequest;
use Domain\RemoteUpload\Actions\GetContentFromExternalSource;
use Domain\UploadRequest\Actions\CreateUploadRequestRootFolderAction;

class UploadFilesRemotelyForUploadRequestController
{
    public function __construct(
        public GetContentFromExternalSource $getContentFromExternalSource,
        public CreateUploadRequestRootFolderAction $createUploadRequestRootFolder,
    ) {
    }

    public function __invoke(
        RemoteUploadRequest $request,
        UploadRequest $uploadRequest
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'Files was successfully uploaded.',
        ];

        // Get upload request root folder query
        $folder = Folder::where('id', $uploadRequest->id);

        // Create folder if not exist
        if ($folder->doesntExist()) {
            ($this->createUploadRequestRootFolder)($uploadRequest);
        }

        // Set default parent_id for uploaded file
        if (is_null($request->input('parent_id'))) {
            $request->merge(['parent_id' => $uploadRequest->id]);
        }

        // Set timestamp for auto filling
        cache()->set("auto-filling.$uploadRequest->id", now()->toString());

        // If it isn't broadcasting, download files immediately in the request
        if (isNotBroadcasting()) {
            ($this->getContentFromExternalSource)($request->all(), $uploadRequest->user);

            return response()->json($successMessage, 201);
        }

        // Add links to the upload queue
        ($this->getContentFromExternalSource)
            ->onQueue()
            ->execute($request->all(), $uploadRequest->user);

        return response()->json([
            'type'    => 'success',
            'message' => 'Files were successfully added to the upload queue.',
        ], 201);
    }
}
