<?php
namespace Domain\Browsing\Controllers;

use DB;
use App\Users\Models\User;
use Illuminate\Support\Arr;
use Domain\Files\Models\File;
use App\Users\Models\UserSetting;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Domain\Items\Requests\SearchRequest;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;
use App\Users\Resources\UsersMinimalCollection;

class SpotlightSearchController
{
    public function __invoke(
        SearchRequest $request
    ): UsersMinimalCollection|array {
        // Prepare queries
        $query = remove_accents(
            $request->input('query')
        );

        // Search users
        if ($request->get('filter') === 'users') {
            return $this->searchUsers($query);
        }

        // Search files
        return $this->searchFiles($query);
    }

    private function searchUsers($query): UsersMinimalCollection
    {
        // Prevent to show non admin user searching
        if (Auth::user()->role !== 'admin') {
            abort(401);
        }

        // Get user ids
        $results = UserSetting::search($query)
            ->get()
            ->pluck('user_id');

        return new UsersMinimalCollection(
            User::find($results)
        );
    }

    private function searchFiles(string $query): array
    {
        $user_id = Auth::id();

        // Next get their folder tree for ids extraction
        $folderWithinIds = Folder::with('folders:id,parent_id')
            ->get(['id']);

        // Then get all accessible shared folders within
        $accessible_parent_ids = Arr::flatten([filter_folders_ids($folderWithinIds)]);

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
            ->get()
            ->take(3);

        $folders = Folder::search($query)
            ->constrain($folderConstrain)
            ->get()
            ->take(3);

        // Collect folders and files to single array
        return [
            'folders' => new FolderCollection($folders),
            'files'   => new FilesCollection($files),
        ];
    }
}
