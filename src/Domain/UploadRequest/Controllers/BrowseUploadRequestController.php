<?php
namespace Domain\UploadRequest\Controllers;

use Illuminate\Support\Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Resources\FolderCollection;
use Domain\UploadRequest\Models\UploadRequest;

class BrowseUploadRequestController extends Controller
{
    public function __invoke(UploadRequest $uploadRequest, $id): array
    {
        $rootId = Str::isUuid($id) ? $id : $uploadRequest->id;

        $folders = Folder::with(['parent:id,name'])
            ->where('parent_id', $rootId)
            ->where('user_id', $uploadRequest->user_id)
            ->sortable()
            ->get();

        // TODO: security check
        $files = File::with(['parent:id,name'])
            ->where('parent_id', $rootId)
            ->where('user_id', $uploadRequest->user_id)
            ->sortable()
            ->get()
            ->each(fn ($file) => $file->setUploadRequestPublicUrl($uploadRequest->id));

        // Collect folders and files to single array
        return [
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
            'root'    => new FolderResource(Folder::find($rootId)),
        ];
    }
}
