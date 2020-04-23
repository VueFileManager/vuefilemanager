<?php

namespace App\Http\Controllers\FileFunctions;

use App\Share;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\FileManagerFolder;
use App\FileManagerFile;
use Response;


class EditController extends Controller
{
    /**
     * Create new folder
     *
     * @param Request $request
     * @return array
     */
    public function create_folder(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|integer',
            'name'      => 'string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get parent_id from request
        $parent_id = $request->parent_id === 0 ? 0 : $request->parent_id;

        // Create folder
        $folder = FileManagerFolder::create([
            'user_id'   => Auth::id(),
            'parent_id' => $parent_id,
            'name'      => $request->has('name') ? $request->input('name') : 'New Folder',
            'type'      => 'folder',
            'unique_id' => $this->get_unique_id(),
        ]);

        // Return new folder
        return $folder;
    }

    /**
     * Rename item name
     *
     * @param Request $request
     * @return mixed
     */
    public function rename_item(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|integer',
            'name'      => 'required|string',
            'type'      => 'required|string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user id
        $user_id = Auth::id();

        // Update folder name
        if ($request->type === 'folder') {

            $item = FileManagerFolder::where('unique_id', $request->unique_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $item->name = $request->name;
            $item->save();

        } else {

            $item = FileManagerFile::where('unique_id', $request->unique_id)
                ->where('user_id', $user_id)
                ->firstOrFail();

            $item->name = $request->name;
            $item->save();
        }

        // Return updated item
        return $item;
    }

    /**
     * Delete item
     *
     * @param Request $request
     * @throws \Exception
     */
    public function delete_item(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'unique_id'    => 'required|integer',
            'type'         => 'required|string',
            'force_delete' => 'required|boolean',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user id
        $user = Auth::user();

        // Delete folder
        if ($request->type === 'folder') {

            // Get folder
            $folder = FileManagerFolder::withTrashed()
                ->with(['folders'])
                ->where('user_id', $user->id)
                ->where('unique_id', $request->unique_id)
                ->first();

            // Force delete children files
            if ($request->force_delete) {

                // Get children folder ids
                $child_folders = filter_folders_ids($folder->trashed_folders, 'unique_id');

                // Get children files
                $files = FileManagerFile::onlyTrashed()
                    ->where('user_id', $user->id)
                    ->whereIn('folder_id', Arr::flatten([$request->unique_id, $child_folders]))
                    ->get();

                // Remove all children files
                foreach ($files as $file) {

                    // Delete file
                    Storage::disk('local')->delete('/file-manager/' . $file->basename);

                    // Delete thumbnail if exist
                    if (!is_null($file->thumbnail)) Storage::disk('local')->delete('/file-manager/' . $file->getOriginal('thumbnail'));

                    // Delete file permanently
                    $file->forceDelete();
                }

                // Delete folder record
                $folder->forceDelete();

            } else {

                // Remove folder from user favourites
                $user->favourites()->detach($request->unique_id);

                // Soft delete folder record
                $folder->delete();
            }
        } else {

            $file = FileManagerFile::withTrashed()
                ->where('user_id', $user->id)
                ->where('unique_id', $request->unique_id)
                ->first();

            if ($request->force_delete) {

                // Delete file
                Storage::disk('local')->delete('/file-manager/' . $file->basename);

                // Delete thumbnail if exist
                if ($file->thumbnail) Storage::disk('local')->delete('/file-manager/' . $file->getOriginal('thumbnail'));

                // Delete file permanently
                $file->forceDelete();
            } else {

                // Soft delete file
                $file->delete();
            }
        }
    }

    /**
     * Upload items
     *
     * @param Request $request
     * @return array
     */
    public function upload_item(Request $request)
    {
        // Check if user can upload
        if (config('vuefilemanager.limit_storage_by_capacity') && user_storage_percentage() >= 100) {

            abort(423, 'You exceed your storage limit!');
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|integer',
            'file'      => 'required|file',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get parent_id from request
        $folder_id = $request->parent_id === 0 ? 0 : $request->parent_id;
        $file = $request->file('file');

        // File
        $filename = Str::random() . '-' . str_replace(' ', '', $file->getClientOriginalName());
        $filetype = get_file_type($file);
        $thumbnail = null;
        $filesize = $file->getSize();
        $directory = 'file-manager';

        // create directory if not exist
        if (!Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->makeDirectory($directory);
        }

        // Store to disk
        Storage::disk('local')->putFileAs($directory, $file, $filename, 'public');

        // Create image thumbnail
        if ($filetype == 'image') {

            $thumbnail = 'thumbnail-' . $filename;

            // Create intervention image
            $image = Image::make($file->getRealPath())->orientate();

            $image->resize(256, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to s3
            Storage::disk('local')->put($directory . '/' . $thumbnail, $image);
        }

        // Store file
        $new_file = FileManagerFile::create([
            'user_id'   => Auth::id(),
            'name'      => pathinfo($file->getClientOriginalName())['filename'],
            'basename'  => $filename,
            'folder_id' => $folder_id,
            'mimetype'  => $file->getClientOriginalExtension(),
            'filesize'  => $filesize,
            'type'      => $filetype,
            'thumbnail' => $thumbnail,
            'unique_id' => $this->get_unique_id(),
        ]);

        return $new_file;
    }

    /**
     * Move item
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function move_item(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'from_unique_id' => 'required|integer',
            'to_unique_id'   => 'required|integer',
            'from_type'      => 'required|string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user id
        $user_id = Auth::id();

        if ($request->from_type === 'folder') {

            // Move folder
            $item = FileManagerFolder::where('user_id', $user_id)
                ->where('unique_id', $request->from_unique_id)
                ->firstOrFail();

            $item->parent_id = $request->to_unique_id;

        } else {

            // Move file under new folder
            $item = FileManagerFile::where('user_id', $user_id)
                ->where('unique_id', $request->from_unique_id)
                ->firstOrFail();

            $item->folder_id = $request->to_unique_id;
        }

        $item->update();

        return response('Done!', 204);
    }

    /**
     * Get unique id
     *
     * @return int
     */
    private function get_unique_id(): int
    {
        // Get files and folders
        $folders = FileManagerFolder::withTrashed()->get();
        $files = FileManagerFile::withTrashed()->get();

        // Get last ids
        $folders_unique = $folders->isEmpty() ? 0 : $folders->last()->unique_id;
        $files_unique = $files->isEmpty() ? 0 : $files->last()->unique_id;

        // Count new unique id
        $unique_id = $folders_unique > $files_unique ? $folders_unique + 1 : $files_unique + 1;

        return $unique_id;
    }
}