<?php
namespace Support\Services;

use Illuminate\Support\Arr;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;

class HelperService
{
    /**
     * Check access to requested directory
     *
     * @param int|array $requested_id
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
        if (is_array($requested_id)) {
            foreach ($requested_id as $id) {
                if (! in_array($id, $accessible_folder_ids)) {
                    abort(403);
                }
            }
        }

        if (! is_array($requested_id)) {
            if (! in_array($requested_id, $accessible_folder_ids)) {
                abort(403);
            }
        }
    }

    /**
     * Check user file access
     *
     * @param $shared
     * @param $file
     */
    public function check_guest_access_to_shared_items($shared, $file): void
    {
        // Check by parent folder permission
        if ($shared->type === 'folder') {
            $this->check_item_access($file->folder_id, $shared);
        }

        // Check by single file permission
        if ($shared->type === 'file') {
            if ($shared->item_id !== $file->id) {
                abort(403);
            }
        }
    }

    /**
     * @param Share $shared
     */
    public function check_protected_share_record(Share $shared): void
    {
        if ($shared->is_protected) {
            $abort_message = "Sorry, you don't have permission";

            if (! request()->hasCookie('share_session')) {
                abort(403, $abort_message);
            }

            // Get shared session
            $share_session = json_decode(
                request()->cookie('share_session')
            );

            // Check if is requested same share record
            if ($share_session->token !== $shared->token) {
                abort(403, $abort_message);
            }

            // Check if share record was authenticated previously via ShareController@authenticate
            if (! $share_session->authenticated) {
                abort(403, $abort_message);
            }
        }
    }
}
