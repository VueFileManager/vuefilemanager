<?php

namespace App\Http\Controllers\FileBrowser;

use App\Http\Requests\FileBrowser\SearchRequest;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\FileManagerFolder;
use App\FileManagerFile;
use App\Share;

class BrowseController extends Controller
{

    /**
     * Get trashed files
     *
     * @return Collection
     */
    public function trash()
    {
        // Get user id
        $user_id = Auth::id();

        // Get folders and files
        $folders_trashed = FileManagerFolder::onlyTrashed()
            ->with(['trashed_folders', 'parent'])
            ->where('user_id', $user_id)
            ->get(['parent_id', 'unique_id', 'name']);

        $folders = FileManagerFolder::onlyTrashed()
            ->with(['parent'])
            ->where('user_id', $user_id)
            ->whereIn('unique_id', filter_folders_ids($folders_trashed))
            ->sortable()
            ->get();

        // Get files trashed
        $files_trashed = FileManagerFile::onlyTrashed()
            ->with(['parent'])
            ->where('user_id', $user_id)
            ->whereNotIn('folder_id', array_values(array_unique(recursiveFind($folders_trashed->toArray(), 'unique_id'))))
            ->sortable()
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files_trashed])->collapse();
    }

    /**
     * Get user shared items
     *
     * @return Collection
     */
    public function shared()
    {
        // Get user
        $user_id = Auth::id();

        // Get shared folders and files
        $folder_ids = Share::where('user_id', $user_id)
            ->where('type', 'folder')
            ->pluck('item_id');

        $file_ids = Share::where('user_id', $user_id)
            ->where('type', '!=', 'folder')
            ->pluck('item_id');

        // Get folders and files
        $folders = FileManagerFolder::with(['parent', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->where('user_id', $user_id)
            ->whereIn('unique_id', $folder_ids)
            ->sortable()
            ->get();

        $files = FileManagerFile::with(['parent', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->where('user_id', $user_id)
            ->whereIn('unique_id', $file_ids)
            ->sortable()
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Get latest user uploads
     *
     * @return mixed
     */
    public function latest() {

        // Get User
        $user = User::with(['latest_uploads' => function($query) {
            $query->sortable(['created_at' => 'desc']); 
        }])
            ->where('id', Auth::id())
            ->first();

        return $user->latest_uploads->makeHidden(['user_id', 'basename']);
    }

    /**
     * Get participant uploads
     *
     * @return mixed
     */
    public function participant_uploads() {

        // Get User
        $uploads = FileManagerFile::with(['parent'])
            ->where('user_id', Auth::id())
            ->whereUserScope('editor')
            ->sortable()
            ->get();

        return $uploads;
    }

    /**
     * Get directory with files
     *
     * @param Request $request
     * @param $unique_id
     * @return Collection
     */
    public function folder(Request $request, $unique_id)
    {
        // Get user
        $user_id = Auth::id();

        // Get folder trash items
        if ($request->query('trash')) {

            // Get folders and files
            $folders = FileManagerFolder::onlyTrashed()
                ->with('parent')
                ->where('user_id', $user_id)
                ->where('parent_id', $unique_id)
                ->sortable()
                ->get();

            $files = FileManagerFile::onlyTrashed()
                ->with('parent')
                ->where('user_id', $user_id)
                ->where('folder_id', $unique_id)
                ->sortable()
                ->get();

            // Collect folders and files to single array
            return collect([$folders, $files])->collapse();
        }

        // Get folders and files
        $folders = FileManagerFolder::with(['parent', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->where('user_id', $user_id)
            ->where('parent_id', $unique_id)
            ->sortable()
            ->get();

        $files = FileManagerFile::with(['parent', 'shared:token,id,item_id,permission,protected,expire_in'])
            ->where('user_id', $user_id)
            ->where('folder_id', $unique_id)
            ->sortable()
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Get user folder tree
     *
     * @return array
     */
    public function navigation_tree() {

        $folders = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('parent_id', 0)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get(['id', 'parent_id', 'unique_id', 'name']);

        return [
            [
                'unique_id' => 0,
                'name'      => __t('home'),
                'location'  => 'base',
                'folders'  => $folders,
            ]
        ];
    }

    /**
     * Search files
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(SearchRequest $request)
    {
        // Get user
        $user_id = Auth::id();
        $query = remove_accents($request->input('query'));

        // Search files id db
        $searched_files = FileManagerFile::search($query)
            ->where('user_id', $user_id)
            ->get();
        $searched_folders = FileManagerFolder::search($query)
            ->where('user_id', $user_id)
            ->get();

        // Collect folders and files to single array
        return collect([$searched_folders, $searched_files])->collapse();
    }
}
