<?php

namespace App\Http\Controllers\FileBrowser;

use App\Http\Requests\FileBrowser\SearchRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\File;
use App\Models\Share;

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
        $folders_trashed = Folder::onlyTrashed()
            ->with(['trashed_folders', 'parent'])
            ->where('user_id', $user_id)
            ->get(['parent_id', 'unique_id', 'name']);

        $folders = Folder::onlyTrashed()
            ->with(['parent'])
            ->where('user_id', $user_id)
            ->whereIn('unique_id', filter_folders_ids($folders_trashed))
            ->sortable()
            ->get();

        // Get files trashed
        $files_trashed = File::onlyTrashed()
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
        $folders = Folder::with(['parent', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('user_id', $user_id)
            ->whereIn('unique_id', $folder_ids)
            ->sortable()
            ->get();

        $files = File::with(['parent', 'shared:token,id,item_id,permission,is_protected,expire_in'])
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
    public function latest()
    {
        $user = User::with(['latest_uploads' => function ($query) {
            $query->sortable(['created_at' => 'desc']);
        }])
            ->where('id', Auth::id())
            ->first();

        return $user->latest_uploads;
    }

    /**
     * Get participant uploads
     *
     * @return mixed
     */
    public function participant_uploads()
    {

        // Get User
        $uploads = File::with(['parent'])
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
     * @param $id
     * @return Collection
     */
    public function folder(Request $request, $id)
    {
        // Get folder trash items
        if ($request->query('trash')) {

            // Get folders and files
            $folders = Folder::onlyTrashed()
                ->with('parent')
                ->where('parent_id', $id)
                ->sortable()
                ->get();

            $files = File::onlyTrashed()
                ->with('parent')
                ->where('folder_id', $id)
                ->sortable()
                ->get();

            // Collect folders and files to single array
            return collect([$folders, $files])->collapse();
        }

        // Get folders and files
        $folders = Folder::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('parent_id', $id)
            ->sortable()
            ->get();

        $files = File::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('folder_id', $id)
            ->sortable()
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }

    /**
     * Get user folder tree
     *
     * @return array
     */
    public function navigation_tree()
    {
        $folders = Folder::with('folders:id,parent_id,id,name')
            ->where('parent_id', null)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'name'      => __('vuefilemanager.home'),
                'location'  => 'base',
                'folders'   => $folders,
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
        $searched_files = File::search($query)
            ->where('user_id', $user_id)
            ->get();
        $searched_folders = Folder::search($query)
            ->where('user_id', $user_id)
            ->get();

        // Collect folders and files to single array
        return collect([$searched_folders, $searched_files])->collapse();
    }
}
