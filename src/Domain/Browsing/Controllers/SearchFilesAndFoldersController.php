<?php
namespace Domain\Browsing\Controllers;

use DB;
use Illuminate\Support\Arr;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Domain\Items\Requests\SearchRequest;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;

class SearchFilesAndFoldersController
{
    public function __invoke(
        SearchRequest $request
    ): array {
        $user_id = Auth::id();

        // Prepare queries
        $query = remove_accents(
            $request->input('query')
        );

        // Get "shared with me" folders
        $sharedWithMeFolderIds = DB::table('team_folder_members')
            ->where('user_id', $user_id)
            ->pluck('parent_id');

        // Next get their folder tree for ids extraction
        $folderWithinIds = Folder::with('folders:id,parent_id')
            ->whereIn('parent_id', $sharedWithMeFolderIds)
            ->get(['id']);

        // Then get all accessible shared folders within
        $accessible_parent_ids = Arr::flatten([filter_folders_ids($folderWithinIds), $sharedWithMeFolderIds]);

        // Prepare eloquent builder
        $folder = new Folder();
        $file = new File();

        // Prepare folders constrain
        $folderConstrain = $folder
            ->where('user_id', $user_id)
            ->orWhereIn('id', $accessible_parent_ids);

        // Prepare files constrain
        $fileConstrain = $file
            ->where('user_id', $user_id)
            ->orWhereIn('parent_id', $accessible_parent_ids);

        // Search files and folders
        $files = File::search($query)
            ->constrain($fileConstrain)
            ->get();

        $folders = Folder::search($query)
            ->constrain($folderConstrain)
            ->get();

        // Collect folders and files to single array
        return [
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
        ];
    }
}
