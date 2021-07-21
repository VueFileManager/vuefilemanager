<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Support\Services\HelperService;

/**
 * Browse shared folder
 */
class VisitorBrowseFolderContentController
{
    public function __construct(
        public HelperService $helper,
    ) {
    }

    public function __invoke(
        string $id,
        Share $shared,
    ): Collection {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check if user can get directory
        $this->helper->check_item_access($id, $shared);

        // Get files and folders
        $folders = Folder::where('user_id', $shared->user_id)
            ->where('parent_id', $id)
            ->sortable()
            ->get();

        $files = File::where('user_id', $shared->user_id)
            ->where('folder_id', $id)
            ->sortable()
            ->get();

        // Set thumbnail links for public files
        $files->map(fn ($file) => $file->setPublicUrl($shared->token));

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }
}
