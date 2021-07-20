<?php
namespace Domain\Folders\Controllers;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;

/**
 * Get navigation tree of shared folder
 */
class VisitorNavigationFolderTreeController extends Controller
{
    public function __construct(
        public HelperService $helper,
    ) {
    }

    public function __invoke(
        Share $shared,
    ): array {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check if user can get directory
        $this->helper->check_item_access($shared->item_id, $shared);

        // Get folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->whereParentId($shared->item_id)
            ->whereUserId($shared->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'id'       => $shared->item_id,
                'name'     => __t('home'),
                'location' => 'public',
                'folders'  => $folders,
            ],
        ];
    }
}
