<?php
namespace Domain\UploadRequest\Controllers;

use Storage;
use Illuminate\Support\Str;
use Domain\Folders\Models\Folder;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Domain\Files\Requests\UploadChunkRequest;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Files\Actions\StoreFileChunksAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Domain\UploadRequest\Actions\CreateUploadRequestRootFolderAction;

class UploadFileChunksForUploadRequestController
{
    public function __construct(
        public ProcessFileAction $processFie,
        public StoreFileChunksAction $storeFileChunks,
        public CreateUploadRequestRootFolderAction $createUploadRequestRootFolder,
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(
        UploadChunkRequest $request,
        UploadRequest $uploadRequest,
    ) {
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

        // Store file chunks
        $chunkPath = ($this->storeFileChunks)($request);

        // Proceed after last chunk
        if ($request->boolean('is_last_chunk')) {
            // Get file name
            $name = Str::uuid() . '.' . $request->input('extension');

            // Move file to user directory
            Storage::disk('local')->move($chunkPath, "files/{$uploadRequest->user->id}/$name");

            // Process file
            $file = ($this->processFie)($request, $uploadRequest->user, $name);

            // Set public access url
            $file->setUploadRequestPublicUrl($uploadRequest->id);

            // Set timestamp for auto filling
            cache()->set("auto-filling.$uploadRequest->id", now()->toString());

            return response(new FileResource($file), 201);
        }
    }
}
