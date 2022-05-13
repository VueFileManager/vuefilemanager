<?php
namespace Domain\Folders\Controllers;

use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Get navigation tree of shared folder
 */
class VisitorNavigationFolderTreeController extends Controller
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        Share $shared,
    ): JsonResponse {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check if user can get directory
        ($this->verifyAccessToItem)($shared->item_id, $shared);

        // Get folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->whereParentId($shared->item_id)
            ->whereUserId($shared->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return response()->json([
            [
                'name'      => __t('home'),
                'location'  => 'public',
                'folders'   => $folders,
                'isMovable' => true,
                'isOpen'    => true,
            ],
        ]);
    }
}
