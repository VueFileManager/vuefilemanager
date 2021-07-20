<?php
namespace Domain\Folders\Controllers;

use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;

class NavigationFolderTreeController
{
    public function __invoke(): array
    {
        $folders = Folder::with('folders:id,parent_id,id,name')
            ->where('parent_id', null)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'name'     => __t('home'),
                'location' => 'base',
                'folders'  => $folders,
            ],
        ];
    }
}
