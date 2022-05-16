<?php
namespace Domain\Teams\Controllers;

use Gate;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;

class NavigationTreeController
{
    public function __invoke(
        Folder $folder
    ): JsonResponse {
        // Get the root team folder
        $teamFolder = $folder->getLatestParent();

        if (! Gate::any(['can-edit', 'can-view'], [$teamFolder, null])) {
            return response()->json(accessDeniedError(), 403);
        }

        $folders = Folder::with('folders:id,parent_id,id,name,team_folder')
            ->where('parent_id', $teamFolder->id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name', 'team_folder']);

        return response()->json([
            [
                'name'      => $teamFolder->name,
                'folders'   => $folders,
                'isMovable' => true,
                'isOpen'    => true,
            ],
        ]);
    }
}
