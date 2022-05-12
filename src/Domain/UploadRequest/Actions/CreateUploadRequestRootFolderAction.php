<?php
namespace Domain\UploadRequest\Actions;

use DB;
use Domain\UploadRequest\Models\UploadRequest;

class CreateUploadRequestRootFolderAction
{
    /**
     * Create root Upload Request folder
     */
    public function __invoke(UploadRequest $uploadRequest): void
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
