<?php

namespace App\Services;

use App\Models\Folder;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class HelperService
{
    /**
     * Delete all user data including files, folders, avatar etc.
     *
     * @param $user
     */
    public function erase_user_data($user)
    {
        // Delete user avatar if exists
        if ($user->settings->getRawOriginal('avatar')) {
            Storage::delete($user->settings->getRawOriginal('avatar'));
        }

        // Delete all user files
        Storage::deleteDirectory("files/$user->id");

        // Delete all user records in database
        collect(['folders', 'files', 'user_settings', 'shares', 'favourite_folder', 'zips'])
            ->each(function ($table) use ($user) {
                DB::table($table)
                    ->whereUserId($user->id)
                    ->delete();
            });
    }

    /**
     * Check access to requested directory
     *
     * @param integer|array $requested_id
     * @param string $shared Shared record detail
     */
    public function check_item_access($requested_id, $shared)
    {
        // Get all children folders
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
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

    /**
     * Check if user has enough space to upload file
     *
     * @param $user_id
     * @param int $file_size
     * @param $temp_filename
     */
    public function check_user_storage_capacity($user_id, int $file_size, $temp_filename): void
    {
        // Get user storage percentage and get storage_limitation setting
        $user_storage_used = user_storage_percentage($user_id, $file_size);

        // Check if user can upload
        if (get_setting('storage_limitation') && $user_storage_used >= 100) {

            // Delete file
            Storage::disk('local')
                ->delete("chunks/$temp_filename");

            // Abort uploading
            // TODO: test pre exceed storage limit
            abort(423, 'You exceed your storage limit!');
        }
    }
}