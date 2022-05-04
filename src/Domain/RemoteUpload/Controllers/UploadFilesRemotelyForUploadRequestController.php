<?php
namespace Domain\RemoteUpload\Controllers;

use DB;
use Domain\Folders\Models\Folder;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\RemoteUpload\Requests\RemoteUploadRequest;
use Domain\RemoteUpload\Actions\GetContentFromExternalSource;

class UploadFilesRemotelyForUploadRequestController
{
    public function __construct(
        private GetContentFromExternalSource $getContentFromExternalSource,
    ) {
    }

    public function __invoke(RemoteUploadRequest $request, UploadRequest $uploadRequest)
    {
        $successMessage = [
            'type'    => 'success',
            'message' => 'Files was successfully uploaded.',
        ];

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
