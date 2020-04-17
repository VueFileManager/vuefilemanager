<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\FileManagerFolder;
use App\FileManagerFile;
use Response;


class FileManagerController extends Controller
{
    /**
     * Get trashed files
     *
     * @param Request $request
     * @return FileManagerFile[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function trash()
    {
        // Get user id
        $user_id = Auth::id();

        // Get folders and files
        $folders_trashed = FileManagerFolder::onlyTrashed()
            ->with(['trashed_folders'])
            ->where('user_id', $user_id)
            ->get(['parent_id', 'unique_id', 'name']);

        $folders = FileManagerFolder::onlyTrashed()
            ->where('user_id', $user_id)
            ->whereIn('unique_id', filter_folders_ids($folders_trashed))
            ->get();

        // Get files trashed
        $files_trashed = FileManagerFile::onlyTrashed()
            ->where('user_id', $user_id)
            ->whereNotIn('folder_id', array_values(array_unique(recursiveFind($folders_trashed->toArray(), 'unique_id'))))
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files_trashed])->collapse();
    }

    /**
     * Get directory with files
     *
     * @return \Illuminate\Support\Collection
     */
    public function folder(Request $request, $unique_id)
    {
        // Get user
        $user_id = Auth::id();

        // Get folder trash items
        if ($request->query('trash')) {

            // Get folders and files
            $folders = FileManagerFolder::onlyTrashed()
                ->where('user_id', $user_id)
                ->with('parent')
                ->where('parent_id', $unique_id)
                ->get();

            $files = FileManagerFile::onlyTrashed()
                ->where('user_id', $user_id)
                ->with('parent')
                ->where('folder_id', $unique_id)
                ->get();

            // Collect folders and files to single array
            return collect([$folders, $files])->collapse();
        }

        // Get folders and files
        $folders = FileManagerFolder::with('parent')
            ->where('user_id', $user_id)
            ->where('parent_id', $unique_id)
            ->get();

        $files = FileManagerFile::with('parent')
            ->where('user_id', $user_id)
            ->where('folder_id', $unique_id)
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Search files
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user
        $user_id = Auth::id();

        // Search files id db
        $searched_files = FileManagerFile::search($request->input('query'))
            ->where('user_id', $user_id)
            ->get();
        $searched_folders = FileManagerFolder::search($request->input('query'))
            ->where('user_id', $user_id)
            ->get();

        // Collect folders and files to single array
        return collect([$searched_folders, $searched_files])->collapse();
    }

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
     * Empty user trash
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function empty_trash()
    {
        // Get user id
        $user_id = Auth::id();

        // Get files and folders
        $folders = FileManagerFolder::onlyTrashed()->where('user_id', $user_id)->get();
        $files = FileManagerFile::onlyTrashed()->where('user_id', $user_id)->get();

        // Force delete folder
        $folders->each->forceDelete();

        // Force delete files
        foreach ($files as $file) {

            // Delete file
            Storage::disk('local')->delete('/file-manager/' . $file->basename);

            // Delete thumbnail if exist
            if ($file->thumbnail) Storage::disk('local')->delete('/file-manager/' . $file->getOriginal('thumbnail'));

            // Delete file permanently
            $file->forceDelete();
        }

        // Return response
        return response('Done!', 200);
    }

    /**
     * Restore item from trash
     *
     * @param Request $request
     */
    public function restore_item(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|integer',
            'type'      => 'required|string',
            'to_home'   => 'boolean',
        ]);

        // Return error
        if ($validator->fails()) abort(400, 'Bad input');

        // Get user id
        $user_id = Auth::id();

        // Get folder
        if ($request->type === 'folder') {

            // Get folder
            $item = FileManagerFolder::onlyTrashed()->where('user_id', $user_id)->where('unique_id', $request->unique_id)->first();

            // Restore item to home directory
            if ($request->has('to_home') && $request->to_home) {
                $item->parent_id = 0;
                $item->save();
            }
        } else {

            // Get item
            $item = FileManagerFile::onlyTrashed()->where('user_id', $user_id)->where('unique_id', $request->unique_id)->first();

            // Restore item to home directory
            if ($request->has('to_home') && $request->to_home) {
                $item->folder_id = 0;
                $item->save();
            }
        }

        // Restore Item
        $item->restore();
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
    }

    /**
     * Get file record
     *
     * @param $unique_id
     * @return mixed
     */
    public function get_file_detail($unique_id)
    {
        // Get user id
        $user_id = Auth::id();

        return FileManagerFile::where('user_id', $user_id)->where('unique_id', $unique_id)->firstOrFail();
    }

    /**
     * Get file
     *
     * @param $filename
     * @return mixed
     */
    public function get_file($filename)
    {
        // Get user id
        $user_id = Auth::id();

        // Get file record
        $file = FileManagerFile::withTrashed()
            ->where('user_id', $user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Get file path
        $path = storage_path() . '/app/file-manager/' . $file->basename;

        // Check if file exist
        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);
        $size = File::size($path);

        // Create response
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Content-Disposition", 'attachment; filename=' . $filename);
        $response->header("Content-Length", $size);
        $response->header("Accept-Ranges", "bytes");
        $response->header("Content-Range", "bytes 0-" . $size . "/" . $size);

        return $response;
    }

    /**
     * Get image thumbnail
     *
     * @param $filename
     * @return mixed
     */
    public function get_thumbnail($filename)
    {
        // Get user id
        $user_id = Auth::id();

        // Get file record
        $file = FileManagerFile::withTrashed()
            ->where('user_id', $user_id)
            ->where('thumbnail', $filename)
            ->firstOrFail();

        // Get file path
        $path = storage_path() . '/app/file-manager/' . $file->getOriginal('thumbnail');

        // Check if file exist
        if (!File::exists($path)) abort(404);

        $file = File::get($path);
        $type = File::mimeType($path);

        // Create response
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
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