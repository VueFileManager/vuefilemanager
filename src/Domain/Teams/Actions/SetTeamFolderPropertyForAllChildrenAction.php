<?php
namespace Domain\Teams\Actions;

use Illuminate\Support\Arr;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;

class SetTeamFolderPropertyForAllChildrenAction
{
    public function __invoke(Folder $folder, bool $isTeamFolder)
    {
        // Get all children of team folder
        $childrenFolderIds = Folder::with('folders:id,parent_id')
            ->where('id', $folder->id)
            ->get('id');

        // Set all children as team_folder = true
        DB::table('folders')
            ->whereIn('id', Arr::flatten(filter_folders_ids($childrenFolderIds)))
            ->update(['team_folder' => $isTeamFolder]);
    }
}
