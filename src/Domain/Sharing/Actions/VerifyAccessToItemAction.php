<?php
namespace Domain\Sharing\Actions;

use Illuminate\Support\Arr;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;

class VerifyAccessToItemAction
{
    /**
     * Check access to requested directory
     */
    public function __invoke(
        string | array $requested_id,
        Share $shared,
    ): void {
        // Get all children folders
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get all authorized parent folders by shared folder as root of tree
        $accessible_parent_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Check user access
        if (is_array($requested_id)) {
            foreach ($requested_id as $id) {
                if (! in_array($id, $accessible_parent_ids)) {
                    abort(403);
                }
            }
        }

        if (! is_array($requested_id)) {
            if (! in_array($requested_id, $accessible_parent_ids)) {
                abort(403);
            }
        }
    }
}
