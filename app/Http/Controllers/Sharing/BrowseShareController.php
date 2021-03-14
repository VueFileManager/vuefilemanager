<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use App\Services\HelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BrowseShareController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Get folders and files
     *
     * @param $id
     * @param $shared
     * @return array
     */
    private function get_items($id, $shared): array
    {
        $folders = Folder::where('user_id', $shared->user_id)
            ->where('parent_id', $id)
            ->sortable()
            ->get();

        $files = File::where('user_id', $shared->user_id)
            ->where('folder_id', $id)
            ->sortable()
            ->get();

        return [$folders, $files];
    }

    /**
     * Search public files
     *
     * @param Request $request
     * @param $token
     * @return Collection
     */
    public function search_public(Request $request, $token)
    {
        // Get shared
        $shared = get_shared($token);

        // Abort if folder is protected
        if ((int)$shared->is_protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Search files id db
        $searched_files = File::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();
        $searched_folders = Folder::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();

        // Get all children content
        $foldersIds = Folder::with('folders:id,parent_id,unique_id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Filter files
        $files = $searched_files->filter(function ($file) use ($accessible_folder_ids, $token) {

            // Set public urls
            $file->setPublicUrl($token);

            // check if item is in accessible folders
            return in_array($file->folder_id, $accessible_folder_ids);
        });

        // Filter folders
        $folders = $searched_folders->filter(function ($folder) use ($accessible_folder_ids) {

            // check if item is in accessible folders
            return in_array($folder->unique_id, $accessible_folder_ids);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Get navigation tree
     *
     * @return array
     */
    public function get_public_navigation_tree($token)
    {
        // Get sharing record
        $shared = get_shared($token);

        // Check if user can get directory
        $this->helper->check_item_access($shared->item_id, $shared);

        // Get folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->where('parent_id', $shared->item_id)
            ->where('user_id', $shared->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'id'       => $shared->item_id,
                'name'     => __('vuefilemanager.home'),
                'location' => 'public',
                'folders'  => $folders,
            ]
        ];
    }

    /**
     * Browse public folders
     *
     * @param $id
     * @param $token
     * @return Collection
     */
    public function get_public_folders($id, $token)
    {
        $shared = get_shared($token);

        // Abort if folder is protected
        if ((int)$shared->is_protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Check if user can get directory
        $this->helper->check_item_access($id, $shared);

        // Get files and folders
        list($folders, $files) = $this->get_items($id, $shared);

        // Set thumbnail links for public files
        $files->map(function ($item) use ($token) {
            $item->setPublicUrl($token);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }
}
