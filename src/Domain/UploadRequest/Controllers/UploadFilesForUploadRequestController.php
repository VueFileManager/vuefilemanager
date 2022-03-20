<?php
namespace Domain\UploadRequest\Controllers;

use DB;
use Domain\Folders\Models\Folder;
use Domain\Files\Resources\FileResource;
use Domain\Files\Actions\UploadFileAction;
use Domain\UploadRequest\Models\UploadRequest;
use App\Users\Exceptions\InvalidUserActionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UploadFilesForUploadRequestController
{
    public function __construct(
        private UploadFileAction $uploadFile,
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

        try {
            // Set default parent_id for uploaded file
            if (is_null($request->input('parent_id'))) {
                $request->merge(['parent_id' => $uploadRequest->id]);
            }

            // Upload file
            $file = ($this->uploadFile)($request, $uploadRequest->user_id);

            // Set public access url
            $file->setUploadRequestPublicUrl($uploadRequest->id);

            // Set timestamp for auto filling
            cache()->set("auto-filling.$uploadRequest->id", now()->toString());

            // Return new uploaded file
            return response(new FileResource($file), 201);
        } catch (InvalidUserActionException $e) {
            return response([
                'type'    => 'error',
                'message' => $e->getMessage(),
            ], 401);
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
