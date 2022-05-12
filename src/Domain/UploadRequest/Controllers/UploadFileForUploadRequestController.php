<?php
namespace Domain\UploadRequest\Controllers;

use Storage;
use Illuminate\Support\Str;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Domain\Files\Requests\UploadFileRequest;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\UploadRequest\Actions\CreateUploadRequestRootFolderAction;

class UploadFileForUploadRequestController
{
    public function __construct(
        public ProcessFileAction $processFie,
        public CreateUploadRequestRootFolderAction $createUploadRequestRootFolder,
    ) {
    }

    public function __invoke(
        UploadFileRequest $request,
        UploadRequest $uploadRequest
    ): JsonResponse {
        // Get upload request root folder query
        $folder = Folder::where('id', $uploadRequest->id);

        // Create folder if it doesn't exist
        if ($folder->doesntExist()) {
            ($this->createUploadRequestRootFolder)($uploadRequest);
        }

        // Set default parent_id for uploaded file
        if (is_null($request->input('parent_id'))) {
            $request->merge(['parent_id' => $uploadRequest->id]);
        }

        // Get file name
        $name = Str::uuid() . '.' . $request->input('extension');

        // Move file to user directory
        Storage::disk('local')->put("files/{$uploadRequest->user->id}/$name", $request->file('file')->get());

        // Process file
        $file = ($this->processFie)($request, $uploadRequest->user, $name);

        // Set public access url
        $file->setUploadRequestPublicUrl($uploadRequest->id);

        // Set timestamp for auto filling
        cache()->set("auto-filling.$uploadRequest->id", now()->toString());

        return response()->json(new FileResource($file), 201);
    }
}
