<?php
namespace Domain\Folders\Controllers;

use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;

class NavigationFolderTreeController
{
    public function __invoke(): array
    {
        $folders = Folder::with('folders:id,parent_id,id,name')
            ->where('parent_id')
            ->where('team_folder', false)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        $teamFolders = Folder::with('folders:id,parent_id,id,name')
            ->where('parent_id')
            ->where('team_folder', true)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'name'     => 'Files',
                'folders'  => $folders,
            ],
            [
                'name'     => 'Team Folders',
                'folders'  => $teamFolders,
            ],
        ];
    }
}
