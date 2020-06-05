<?php

namespace App\Http\Tools;

use App;
use App\Share;
use App\FileManagerFile;
use App\FileManagerFolder;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;


class Editor
{
    /**
     * Create new directory
     *
     * @param $request
     * @param null $shared
     * @return FileManagerFolder|\Illuminate\Database\Eloquent\Model
     */
    public static function create_folder($request, $shared = null)
    {
        // Get variables
        $user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
        $name = $request->has('name') ? $request->input('name') : 'New Folder';
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        // Create folder
        $folder = FileManagerFolder::create([
            'parent_id'  => $request->parent_id,
            'unique_id'  => get_unique_id(),
            'user_scope' => $user_scope,
            'user_id'    => $user_id,
            'type'       => 'folder',
            'name'       => $name,
        ]);

        // Return new folder
        return $folder;
    }

    /**
     * Rename item name
     *
     * @param RenameItemRequest $request
     * @param $unique_id
     * @param null $shared
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function rename_item($request, $unique_id, $shared = null)
    {
        // Get user id
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        // Get item
        $item = get_item($request->type, $unique_id, $user_id);

        // Rename item
        $item->update([
            'name' => $request->name
        ]);

        // Return updated item
        return $item;
    }

    /**
     * Delete file or folder
     *
     * @param $request
     * @param $unique_id
     * @param null $shared
     * @throws \Exception
     */
    public static function delete_item($request, $unique_id, $shared = null)
    {
        // Get user id
        $user = is_null($shared) ? Auth::user() : User::findOrFail($shared->user_id);

        // Delete folder
        if ($request->type === 'folder') {

            // Get folder
            $folder = FileManagerFolder::withTrashed()
                ->with(['folders'])
                ->where('user_id', $user->id)
                ->where('unique_id', $unique_id)
                ->first();

            // Get folder shared record
            $shared = Share::where('user_id', $user->id)
                ->where('type', '=', 'folder')
                ->where('item_id', $unique_id)
                ->first();

            // Delete folder shared record
            if ($shared) {
                $shared->delete();
            }

            // Force delete children files
            if ($request->force_delete) {

                // Get children folder ids
                $child_folders = filter_folders_ids($folder->trashed_folders, 'unique_id');

                // Get children files
                $files = FileManagerFile::onlyTrashed()
                    ->where('user_id', $user->id)
                    ->whereIn('folder_id', Arr::flatten([$unique_id, $child_folders]))
                    ->get();

                // Remove all children files
                foreach ($files as $file) {

                    // Delete file
                    Storage::delete('/file-manager/' . $file->basename);

                    // Delete thumbnail if exist
                    if (!is_null($file->thumbnail)) Storage::delete('/file-manager/' . $file->getOriginal('thumbnail'));

                    // Delete file permanently
                    $file->forceDelete();
                }

                // Delete folder record
                $folder->forceDelete();
            }

            // Soft delete items
            if (!$request->force_delete) {

                // Remove folder from user favourites
                $user->favourites()->detach($unique_id);

                // Soft delete folder record
                $folder->delete();
            }
        }

        // Delete item
        if ($request->type !== 'folder') {

            // Get file
            $file = FileManagerFile::withTrashed()
                ->where('user_id', $user->id)
                ->where('unique_id', $unique_id)
                ->first();

            // Get folder shared record
            $shared = Share::where('user_id', $user->id)
                ->where('type', '=', 'file')
                ->where('item_id', $unique_id)
                ->first();

            // Delete file shared record
            if ($shared) {
                $shared->delete();
            }

            // Force delete file
            if ($request->force_delete) {

                // Delete file
                Storage::delete('/file-manager/' . $file->basename);

                // Delete thumbnail if exist
                if ($file->thumbnail) Storage::delete('/file-manager/' . $file->getOriginal('thumbnail'));

                // Delete file permanently
                $file->forceDelete();
            }

            // Soft delete file
            if (!$request->force_delete) {

                // Soft delete file
                $file->delete();
            }
        }
    }

    /**
     * Upload file
     *
     * @param $request
     * @param null $shared
     * @return FileManagerFile|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function upload($request, $shared = null)
    {
        // Get user data
        $user_scope = is_null($shared) ? $request->user()->token()->scopes[0] : 'editor';
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        // Get parent_id from request
        $folder_id = $request->parent_id === 0 ? 0 : $request->parent_id;
        $file = $request->file('file');

        // File
        $filename = Str::random() . '-' . str_replace(' ', '', $file->getClientOriginalName());
        $filetype = get_file_type($file);
        $filesize = $file->getSize();
        $directory = 'file-manager';
        $thumbnail = null;

        // create directory if not exist
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Store to disk
        Storage::putFileAs($directory, $file, $filename, 'private');

        // Create image thumbnail
        if (in_array($file->getMimeType(), ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {

            // Get thumbnail name
            $thumbnail = 'thumbnail-' . $filename;

            // Create intervention image
            $image = Image::make($file->getRealPath())->orientate();

            // Resize image
            $image->resize(564, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to disk
            Storage::put($directory . '/' . $thumbnail, $image);

        } elseif ($file->getMimeType() == 'image/svg+xml') {

            $thumbnail = $filename;
        }

        // Store file
        $options = [
            'name'       => pathinfo($file->getClientOriginalName())['filename'],
            'mimetype'   => $file->getClientOriginalExtension(),
            'unique_id'  => get_unique_id(),
            'user_scope' => $user_scope,
            'folder_id'  => $folder_id,
            'thumbnail'  => $thumbnail,
            'basename'   => $filename,
            'filesize'   => $filesize,
            'type'       => $filetype,
            'user_id'    => $user_id,
        ];

        // Return new file
        return FileManagerFile::create($options);
    }

    /**
     * Move folder or file to new location
     *
     * @param $request
     * @param $unique_id
     * @param null $shared
     */
    public static function move($request, $unique_id, $shared = null)
    {
        // Get user id
        $user_id = is_null($shared) ? Auth::id() : $shared->user_id;

        if ($request->from_type === 'folder') {

            // Move folder
            $item = FileManagerFolder::where('user_id', $user_id)
                ->where('unique_id', $unique_id)
                ->firstOrFail();

            $item->update([
                'parent_id' => $request->to_unique_id
            ]);

        } else {

            // Move file under new folder
            $item = FileManagerFile::where('user_id', $user_id)
                ->where('unique_id', $unique_id)
                ->firstOrFail();

            $item->update([
                'folder_id' => $request->to_unique_id
            ]);
        }
    }
}