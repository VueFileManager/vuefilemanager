<?php
namespace Domain\Teams\Controllers;

use Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BrowseSharedWithMeController
{
    public function __invoke($id): array
    {
        $rootId = Str::isUuid($id) ? $id : null;
        $requestedFolder = Str::isUuid($id) ? Folder::findOrFail($rootId) : null;

        $relations = [
            'parent:id,name',
            'shared:token,id,item_id,permission,is_protected,expire_in',
        ];

        if ($rootId) {
            $folders = Folder::with($relations)
                ->where('id', $id)
                ->sortable()
                ->get();

            $files = File::with($relations)
                ->where('folder_id', $id)
                ->sortable()
                ->get();
        }

        if (! $rootId) {
            $folderIds = DB::table('team_folder_members')
                ->where('user_id', Auth::id())
                ->pluck('folder_id');

            $folders = Folder::with($relations)
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
