<?php
namespace Domain\Folders\Controllers;

use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NavigationTreeController
{
    public function __invoke(): array
    {
        // Get signed user folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->where('parent_id')
            ->where('user_id', Auth::id())
            ->sortable()
            ->get(['id', 'parent_id', 'name']);

        return [
            [
                'location'  => 'files',
                'name'      => __t('menu.files'),
                'folders'   => $folders,
                'isMovable' => true,
                'isOpen'    => true,
            ],
        ];
    }
}
