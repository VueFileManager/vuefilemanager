<?php
namespace Domain\Browsing\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Folders\Resources\FolderResource;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Browse shared folder
 */
class VisitorBrowseFolderController
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        string $id,
        Share $shared,
        Request $request,
    ): array {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check if user can get directory
        ($this->verifyAccessToItem)($id, $shared);

        // Get requested folder
        $requestedFolder = Folder::findOrFail($id);

        // Get files and folders
        $folders = Folder::where('user_id', $shared->user_id)
            ->where('parent_id', $id)
            ->sortable()
            ->get();

        $files = File::where('user_id', $shared->user_id)
            ->where('parent_id', $id)
            ->sortable()
            ->get();

        // Set thumbnail links for public files
        $files->map(fn ($file) => $file->setSharedPublicUrl($shared->token));

        list($data, $paginate, $links) = groupPaginate($request, $folders, $files);

        return [
            'data'  => $data,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => new FolderResource($requestedFolder),
            ],
        ];
    }
}
