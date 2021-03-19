<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
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
     * Browse public folders
     *
     * @param $id
     * @param Share $shared
     * @return Collection
     */
    public function browse_folder($id, Share $shared)
    {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check if user can get directory
        $this->helper->check_item_access($id, $shared);

        // Get files and folders
        list($folders, $files) = $this->helper->get_items_under_shared_by_folder_id($id, $shared);

        // Set thumbnail links for public files
        $files->map(function ($file) use ($shared) {
            $file->setPublicUrl($shared->token);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }

    /**
     * Search public files
     *
     * @param Request $request
     * @param Share $shared
     * @return Collection
     */
    public function search(Request $request, Share $shared)
    {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Search files id db
        $searched_files = File::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();
        $searched_folders = Folder::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();

        // Get all children content
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Filter files
        $files = $searched_files->filter(function ($file) use ($accessible_folder_ids, $shared) {

            // Set public urls
            $file->setPublicUrl($shared->token);

            // check if item is in accessible folders
            return in_array($file->folder_id, $accessible_folder_ids);
        });

        // Filter folders
        $folders = $searched_folders->filter(function ($folder) use ($accessible_folder_ids) {

            // check if item is in accessible folders
            return in_array($folder->id, $accessible_folder_ids);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Get navigation tree
     *
     * @param Share $shared
     * @return array
     */
    public function navigation_tree(Share $shared)
    {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

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
}
