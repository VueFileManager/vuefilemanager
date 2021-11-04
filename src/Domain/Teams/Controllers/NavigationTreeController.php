<?php
namespace Domain\Teams\Controllers;

use Gate;
use Domain\Folders\Models\Folder;

class NavigationTreeController
{
    public function __invoke(Folder $folder): array
    {
        $teamFolder = $folder->getLatestParent();

        if (! Gate::any(['can-edit', 'can-view'], [$teamFolder, null])) {
            abort(403, 'Access Denied');
        }

        $folders = Folder::with('folders:id,parent_id,id,name,team_folder')
            ->where('parent_id', $teamFolder->id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name', 'team_folder']);

        return [
            [
                'name'      => $teamFolder->name,
                'folders'   => $folders,
                'isMovable' => true,
                'isOpen'    => true,
            ],
        ];
    }
}
