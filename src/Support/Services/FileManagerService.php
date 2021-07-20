<?php
namespace Support\Services;

use DB;
use App\Users\Models\User;
use Illuminate\Support\Arr;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Domain\Files\Models\File as UserFile;
use Domain\Items\Requests\RenameItemRequest;

class FileManagerService
{
    public function __construct(
        private HelperService $helper,
    ) {
    }

    /**
     * Rename item name
     *
     * @param RenameItemRequest $request
     * @param $id
     * @param null $shared
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function rename_item($request, $id, $shared = null)
    {
        // Get user id
        $user_id = $shared ? $shared->user_id : Auth::id();

        // Get item
        $item = get_item($request->type, $id, $user_id);

        // Rename item
        $item->update([
            'name' => $request->name,
        ]);

        // Return updated item
        return $item;
    }

    /**
     * Delete file or folder
     *
     * @param $item
     * @param $id
     * @param null $shared
     * @throws \Exception
     */
    public function delete_item($item, $id, $shared = null)
    {
        // Delete folder
        if ($item['type'] === 'folder') {
            // Get folder
            $folder = Folder::withTrashed()
                ->with('folders')
                ->find($id);

            // Get folder shared record
            $shared = Share::where('type', 'folder')
                ->where('item_id', $id)
                ->first();

            // Delete folder shared record
            if ($shared) {
                $shared->delete();
            }

            // Remove folder from user favourites
            DB::table('favourite_folder')
                ->where('folder_id', $folder->id)
                ->delete();

            // Soft delete items
            if (! $item['force_delete']) {
                // Soft delete folder record
                $folder->delete();
            }

            // Force delete children files
            if ($item['force_delete']) {
                // Get children folder ids
                $child_folders = filter_folders_ids($folder->trashedFolders, 'id');

                // Get children files
                $files = UserFile::onlyTrashed()
                    ->whereIn('folder_id', Arr::flatten([$id, $child_folders]))
                    ->get();

                // Remove all children files
                foreach ($files as $file) {
                    // Delete file
                    Storage::delete("/files/$file->user_id/$file->basename");

                    // Delete thumbnail if exist
                    if ($file->thumbnail) {
                        Storage::delete(
                            "/files/$file->user_id/{$file->getRawOriginal('thumbnail')}"
                        );
                    }

                    // Delete file permanently
                    $file->forceDelete();
                }

                // Delete folder record
                $folder->forceDelete();
            }
        }

        // Delete item
        if ($item['type'] !== 'folder') {
            // Get file
            $file = UserFile::withTrashed()
                ->find($id);

            // Get folder shared record
            $shared = Share::where('type', 'file')
                ->where('item_id', $id)
                ->first();

            // Delete file shared record
            if ($shared) {
                $shared->delete();
            }

            // Force delete file
            if ($item['force_delete']) {
                // Delete file
                Storage::delete("/files/$file->user_id/$file->basename");

                // Delete thumbnail if exist
                if ($file->thumbnail) {
                    Storage::delete(
                        "/files/$file->user_id/{$file->getRawOriginal('thumbnail')}"
                    );
                }

                // Delete file permanently
                $file->forceDelete();
            }

            // Soft delete file
            if (! $item['force_delete']) {
                // Soft delete file
                $file->delete();
            }
        }
    }

    /**
     * Move folder or file to new location
     *
     * @param $request
     * @param $to_id
     */
    public function move($request, $to_id)
    {
        foreach ($request->items as $item) {
            // Move folder
            if ($item['type'] === 'folder') {
                Folder::find($item['id'])
                    ->update(['parent_id' => $to_id]);
            }

            // Move file
            if ($item['type'] !== 'folder') {
                UserFile::find($item['id'])
                    ->update(['folder_id' => $to_id]);
            }
        }
    }

    /**
     * Store folder icon
     *
     * @param $request
     * @param $id
     */
    public function edit_folder_properties($request, $id)
    {
        // Get folder
        $folder = Folder::find($id);

        // Set default folder icon
        if ($request->emoji === 'default') {
            $folder->update([
                'emoji' => null,
                'color' => null,
            ]);
        }

        // Set emoji
        if ($request->filled('emoji')) {
            $folder->update([
                'emoji' => $request->emoji,
                'color' => null,
            ]);
        }

        // Set color
        if ($request->filled('color')) {
            $folder->update([
                'emoji' => null,
                'color' => $request->color,
            ]);
        }
    }
}
