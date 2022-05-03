<?php
namespace Domain\Items\Actions;

use Gate;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Teams\Actions\SetTeamFolderPropertyForAllChildrenAction;

class MoveFileOrFolderAction
{
    public function __construct(
        public SetTeamFolderPropertyForAllChildrenAction $setTeamFolderPropertyForAllChildren,
    ) {
    }

    /**
     * Move folder or file to new location
     */
    public function __invoke($request, ?Share $share = null): void
    {
        foreach ($request->input('items') as $item) {
            $entry = get_item($item['type'], $item['id']);

            // Check permission
            Gate::authorize('can-edit', [$entry, $share]);

            // Process folder
            if ($item['type'] === 'folder') {
                // Determine, if we are moving folder into the team folder or not
                $isTeamFolder = is_null($request->input('to_id'))
                    ? false
                    : Folder::find($request->input('to_id'))->getLatestParent()->team_folder;

                // Change team_folder mark for all children folders
                ($this->setTeamFolderPropertyForAllChildren)($entry, $isTeamFolder);

                // Update folder
                $entry->update([
                    'parent_id'   => $request->input('to_id'),
                    'team_folder' => $isTeamFolder,
                ]);
            }

            // Process file
            if ($item['type'] !== 'folder') {
                $entry->update([
                    'parent_id' => $request->input('to_id'),
                ]);
            }
        }
    }
}
