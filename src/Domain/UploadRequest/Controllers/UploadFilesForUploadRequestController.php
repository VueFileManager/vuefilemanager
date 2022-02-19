<?php

namespace Domain\UploadRequest\Controllers;

use App\Users\Exceptions\InvalidUserActionException;
use Domain\Files\Resources\FileResource;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Files\Actions\UploadFileAction;
use Domain\Folders\Models\Folder;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use DB;

class UploadFilesForUploadRequestController
{
    public function __construct(
        private UploadFileAction $uploadFile,
    ) {}

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(\Domain\Files\Requests\UploadRequest $request, UploadRequest $uploadRequest)
    {
        // Check if upload request is active
        if ($uploadRequest->status !== 'active') {
            return response('Gone', 410);
        }

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
        $timestampName = format_date($uploadRequest->created_at, 'd. M. Y');

        DB::table('folders')->insert([
            'id'        => $uploadRequest->id,
            'parent_id' => $uploadRequest->folder_id,
            'user_id'   => $uploadRequest->user_id,
            'name'      => "Upload Request from $timestampName",
        ]);
    }
}