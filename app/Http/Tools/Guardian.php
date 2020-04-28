<?php

namespace App\Http\Tools;

use App;
use App\FileManagerFolder;
use Illuminate\Support\Arr;


class Guardian
{
    /**
     * Check access to requested directory
     *
     * @param integer|array $requested_id
     * @param string $shared Shared record detail
     */
    public static function check_item_access($requested_id, $shared)
    {
        // Get all children folders
        $foldersIds = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get all authorized parent folders by shared folder as root of tree
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Check user access
        if ( is_array($requested_id) ) {
            foreach ($requested_id as $id) {
                if (!in_array($id, $accessible_folder_ids))
                    abort(403);
            }
        }

        if (! is_array($requested_id)) {
            if (! in_array($requested_id, $accessible_folder_ids))
                abort(403);
        }
    }
}