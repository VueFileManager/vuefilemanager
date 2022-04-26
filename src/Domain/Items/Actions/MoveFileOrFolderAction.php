<?php
namespace Domain\Items\Actions;

use Gate;
use Domain\Sharing\Models\Share;

class MoveFileOrFolderAction
{
    /**
     * Move folder or file to new location
     */
    public function __invoke($request, ?Share $share = null): void
    {
        foreach ($request->input('items') as $item) {
            $entry = get_item($item['type'], $item['id']);

            // Check permission
            Gate::authorize('can-edit', [$entry, $share]);

            // Update item
            $entry->update([
                'parent_id'   => $request->input('to_id'),
            ]);
        }
    }
}
