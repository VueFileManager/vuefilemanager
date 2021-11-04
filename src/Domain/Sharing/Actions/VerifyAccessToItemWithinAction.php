<?php
namespace Domain\Sharing\Actions;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;

class VerifyAccessToItemWithinAction
{
    public function __construct(
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    /**
     * Check user file access
     */
    public function __invoke(
        Share $shared,
        File $file
    ): void {
        // Check by parent folder permission
        if ($shared->type === 'folder') {
            ($this->verifyAccessToItem)($file->parent_id, $shared);
        }

        // Check by single file permission
        if ($shared->type === 'file') {
            if ($shared->item_id !== $file->id) {
                abort(403);
            }
        }
    }
}
