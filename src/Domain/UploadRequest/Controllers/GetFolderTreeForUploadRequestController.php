<?php
namespace Domain\UploadRequest\Controllers;

use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\UploadRequest\Models\UploadRequest;

class GetFolderTreeForUploadRequestController extends Controller
{
    public function __invoke(
        UploadRequest $uploadRequest
    ): JsonResponse {
        // Get folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->where('parent_id', $uploadRequest->id)
            ->where('user_id', $uploadRequest->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return response()->json([
            [
                'name'      => __t('upload_request'),
                'location'  => 'upload-request',
                'folders'   => $folders,
                'isMovable' => true,
                'isOpen'    => true,
            ],
        ]);
    }
}
