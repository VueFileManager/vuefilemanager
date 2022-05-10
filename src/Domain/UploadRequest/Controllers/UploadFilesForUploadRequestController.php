<?php
namespace Domain\UploadRequest\Controllers;

use DB;
use Storage;
use Illuminate\Support\Str;
use Domain\Folders\Models\Folder;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\ProcessFileAction;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Files\Actions\StoreFileChunksAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UploadFilesForUploadRequestController
{
    public function __construct(
        private ProcessFileAction $processFie,
        private StoreFileChunksAction $storeFileChunks,
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(\Domain\Files\Requests\UploadRequest $request, UploadRequest $uploadRequest)
    {
        // Get upload request root folder query
        $folder = Folder::where('id', $uploadRequest->id);

        // Create folder if not exist
        if ($folder->doesntExist()) {
            $this->createFolder($uploadRequest);
        }

        // Set default parent_id for uploaded file
        if (is_null($request->input('parent_id'))) {
            $request->merge(['parent_id' => $uploadRequest->id]);
        }

        // Store file chunks
        $chunkPath = ($this->storeFileChunks)($request);

        // Proceed after last chunk
        if ($request->boolean('is_last')) {
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

    /**
     * Create root Upload Request folder
     */
    private function createFolder(UploadRequest $uploadRequest): void
    {
        // Format timestamp
        $timestamp = format_date($uploadRequest->created_at, 'd. M. Y');

        // Create folder
        DB::table('folders')->insert([
            'id'         => $uploadRequest->id,
            'parent_id'  => $uploadRequest->folder_id ?? null,
            'user_id'    => $uploadRequest->user_id,
            'name'       => $uploadRequest->name ?? __t('upload_request_default_folder', ['timestamp' => $timestamp]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update upload request status
        $uploadRequest->update([
            'status' => 'filling',
        ]);
    }
}
