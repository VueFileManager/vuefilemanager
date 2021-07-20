<?php
namespace Domain\Browsing\Controllers;

use Domain\Sharing\Models\Share;
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
        list($folders, $files) = $this->helper->get_items_under_shared_by_folder_id($id, $shared);

        // Set thumbnail links for public files
        $files->map(function ($file) use ($shared) {
            $file->setPublicUrl($shared->token);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }
}
