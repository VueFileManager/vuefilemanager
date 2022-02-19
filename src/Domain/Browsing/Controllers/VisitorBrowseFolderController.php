<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Resources\FolderCollection;
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

        return [
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
            'root'    => new FolderResource($requestedFolder),
        ];
    }
}
