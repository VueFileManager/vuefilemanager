<?php
namespace Domain\Items\Actions;

use Domain\Sharing\Models\Share;
use Gate;

class MoveFileOrFolderAction
{
    /**
     * Move folder or file to new location
     */
    public function __invoke($request, ?Share $share = null): void
    {
        foreach ($request->input('items') as $item) {
            
            $item = get_item($item['type'], $item['id']);
            
            Gate::authorize('can-edit', [$item, $share]);

            $item->update([
                'parent_id' => $request->input('to_id'),
            ]);
        }
    }
}
