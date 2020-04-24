<?php

namespace App\Http\Controllers;

use App\FileManagerFolder;
use App\Share;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\FileManagerFile;
use Illuminate\Support\Str;
use Response;

class FileAccessController extends Controller
{
    /**
     * Get avatar
     *
     * @param $basename
     * @return mixed
     */
    public function get_avatar($basename)
    {
        // Get file path
        $path = storage_path() . '/app/avatars/' . $basename;

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
     * Get file
     *
     * @param Request $request
     * @param $filename
     * @return mixed
     */
    public function get_file(Request $request, $filename)
    {
        // Get user id
        $user_id = Auth::id();

        // Get file record
        $file = FileManagerFile::withTrashed()
            ->where('user_id', $user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Check user permission
        if ( ! $request->user()->tokenCan('master') ) {
            $this->check_access($request, $file);
        }

        // Format pretty filename
        $file_pretty_name = $file->name . '.' . $file->mimetype;

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
        $response->header("Content-Disposition", 'attachment; filename=' . $file_pretty_name);
        $response->header("Content-Length", $size);
        $response->header("Accept-Ranges", "bytes");
        $response->header("Content-Range", "bytes 0-" . $size . "/" . $size);

        return $response;
    }

    /**
     * Get image thumbnail
     *
     * @param Request $request
     * @param $filename
     * @return mixed
     */
    public function get_thumbnail(Request $request, $filename)
    {
        // Get file record
        $file = FileManagerFile::withTrashed()
            ->where('user_id', $request->user()->id)
            ->where('thumbnail', $filename)
            ->firstOrFail();

        // Check user permission
        if ( ! $request->user()->tokenCan('master') ) {
            $this->check_access($request, $file);
        }

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
     * Check user file access
     *
     * @param $request
     */
    protected function check_access($request, $file): void
    {
        // check if shared_token cookie exist
        if (! $request->hasCookie('shared_token')) abort('401');

        // Get shared token
        $shared = Share::where(DB::raw('BINARY `token`'), $request->cookie('shared_token'))
            ->first();

        // Check by parent folder permission
        if ($shared->type === 'folder') {

            // Get all children folders
            $foldersIds = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
                ->where('user_id', $shared->user_id)
                ->where('parent_id', $shared->item_id)
                ->get();

            // Get all authorized parent folders by shared folder as root of tree
            $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

            // Check user access
            if (!in_array($file->folder_id, $accessible_folder_ids)) abort(403);
        }

        // Check by single file permission
        if ($shared->type === 'file') {
            if ($shared->item_id !== $file->unique_id) abort(403);
        }
    }
}
