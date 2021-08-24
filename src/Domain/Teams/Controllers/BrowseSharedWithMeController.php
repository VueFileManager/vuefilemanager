<?php


namespace Domain\Teams\Controllers;


use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Str;

class BrowseSharedWithMeController
{
    public function __invoke($id)
    {
        $rootId = Str::isUuid($id) ? $id : null;
        $requestedFolder = Str::isUuid($id) ? Folder::findOrFail($rootId) : null;

        if ($rootId) {
            $folders = Folder::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
                ->where('id', $id)
                ->sortable()
                ->get();

            $files = File::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
                ->where('folder_id', $id)
                ->sortable()
                ->get();
        }

        if (! $rootId) {

            $folderIds = DB::table('team_folder_members')
                ->where('member_id', Auth::id())
                ->pluck('folder_id');

            $folders = Folder::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
                ->whereIn('id', $folderIds)
                ->sortable()
                ->get();

            $files = [];
        }

        // Collect folders and files to single array
        return [
            'content' => collect([$folders, $files])->collapse(),
            'folder'  => $requestedFolder,
        ];
    }
}