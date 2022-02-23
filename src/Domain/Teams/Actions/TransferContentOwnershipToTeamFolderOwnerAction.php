<?php
namespace Domain\Teams\Actions;

use Illuminate\Support\Arr;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransferContentOwnershipToTeamFolderOwnerAction
{
    public function __invoke(Folder $folder, $leavingUserId)
    {
        // Find and delete attached member from team folder
        DB::table('team_folder_members')
            ->where('parent_id', $folder->id)
            ->where('user_id', $leavingUserId)
            ->delete();

        // Get all inherited folder from team folder
        $childrenFolderIds = Folder::with('folders:id,parent_id')
            ->where('id', $folder->id)
            ->get('id');

        $teamFolderIds = Arr::flatten(filter_folders_ids($childrenFolderIds));

        // Replace leaver content ownership for author of team folder
        DB::table('files')
            ->whereIn('parent_id', $teamFolderIds)
            ->where('user_id', $leavingUserId)
            ->cursor()
            ->each(
                fn ($file) =>
                $this->move_files_to_the_new_destination($file, $folder)
            );

        DB::table('files')
            ->whereIn('parent_id', $teamFolderIds)
            ->where('user_id', $leavingUserId)
            ->update(['user_id' => $folder->user_id]);

        DB::table('folders')
            ->whereIn('id', $teamFolderIds)
            ->where('user_id', $leavingUserId)
            ->update(['user_id' => $folder->user_id]);
    }

    /**
     * @param $file
     * @param Folder $folder
     */
    private function move_files_to_the_new_destination($file, Folder $folder): void
    {
        // Move image thumbnails
        if ($file->type === 'image') {
            // Get image thumbnail list
            $thumbnailList = getThumbnailFileList($file->basename);

            // move thumbnails to the new location
            $thumbnailList->each(function ($basename) use ($file, $folder) {
                $oldPath = "files/$file->user_id/$basename";
                $newPath = "files/$folder->user_id/$basename";

                if (Storage::exists($oldPath)) {
                    Storage::move($oldPath, $newPath);
                }
            });
        }

        // Move single file
        Storage::move("files/$file->user_id/$file->basename", "files/$folder->user_id/$file->basename");
    }
}
