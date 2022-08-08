<?php
namespace Domain\Browsing\Controllers;

use DB;
use App\Users\Models\User;
use Illuminate\Support\Arr;
use Domain\Files\Models\File;
use App\Users\Models\UserSetting;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Domain\Items\Requests\SearchRequest;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;
use App\Users\Resources\UsersMinimalCollection;

class SpotlightSearchController
{
    public function __invoke(
        SearchRequest $request
    ): JsonResponse {
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

    private function searchUsers(
        string $query
    ): JsonResponse {
        // Prevent to show non admin user searching
        if (Auth::user()->role !== 'admin') {
            abort(response()->json([
                'type'    => 'error',
                'message' => 'Access denied. You need administrator privileges to search the users.',
            ]), 403);
        }

        // Get user ids
        $results = UserSetting::search($query)
            ->get()
            ->pluck('user_id');

        $users = new UsersMinimalCollection(
            User::find($results)
        );

        return response()->json($users);
    }

    private function searchFiles(
        string $query
    ): JsonResponse {
        $user_id = Auth::id();

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
            ->get()
            ->take(3);

        $folders = Folder::search($query)
            ->constrain($folderConstrain)
            ->get()
            ->take(3);

        $entries = collect([
            $folders ? json_decode((new FolderCollection($folders))->toJson(), true) : null,
            $files ? json_decode((new FilesCollection($files))->toJson(), true) : null,
        ])->collapse();

        // Collect folders and files to single array
        return response()->json([
            'data' => $entries,
        ]);
    }
}
