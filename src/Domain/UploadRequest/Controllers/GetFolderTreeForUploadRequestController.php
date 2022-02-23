<?php

namespace Domain\UploadRequest\Controllers;

use App\Http\Controllers\Controller;
use Domain\Folders\Models\Folder;
use Domain\UploadRequest\Models\UploadRequest;

class GetFolderTreeForUploadRequestController extends Controller
{
    public function __invoke(UploadRequest $uploadRequest)
    {
        // Check if upload request is active
        if ($uploadRequest->status !== 'active') {
            return response('Gone', 410);
        }

        // Get folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->whereParentId($uploadRequest->id)
            ->whereUserId($uploadRequest->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'name'      => 'Upload Request', // Todo: translate
                'location'  => 'upload-request',
                'folders'   => $folders,
                'isMovable' => true,
                'isOpen'    => true,
            ],
        ];
    }
}