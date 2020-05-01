<?php

namespace App\Http\Controllers;

use App\FileManagerFolder;
use App\Http\Tools\Guardian;
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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to file
            $this->check_file_access($shared, $file);
        }

        return $this->download_file($file);
    }

    /**
     * Get file public
     *
     * @param $filename
     * @param $token
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_file_public($filename, $token)
    {
        // Get sharing record
        $shared = get_shared($token);

        // Abort if shared is protected
        if ($shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Get file record
        $file = FileManagerFile::where('user_id', $shared->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Check file access
        $this->check_file_access($shared, $file);

        return $this->download_file($file);
    }

    /**
     * Get image thumbnail
     *
     * @param Request $request
     * @param $filename
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
            $this->check_file_access($request, $file);
        }

        return $this->thumbnail_file($file);
    }

    /**
     * Get public image thumbnail
     *
     * @param $filename
     * @param $token
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_thumbnail_public($filename, $token)
    {
        // Get sharing record
        $shared = get_shared($token);

        // Abort if thumbnail is protected
        if ($shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Get file record
        $file = FileManagerFile::where('user_id', $shared->user_id)
            ->where('thumbnail', $filename)
            ->firstOrFail();

        // Check file access
        $this->check_file_access($shared, $file);

        return $this->thumbnail_file($file);
    }

    /**
     * Check user file access
     *
     * @param $shared
     * @param $file
     */
    protected function check_file_access($shared, $file): void
    {
        // Check by parent folder permission
        if ($shared->type === 'folder') {
            Guardian::check_item_access($file->folder_id, $shared);
        }

        // Check by single file permission
        if ($shared->type === 'file') {
            if ($shared->item_id !== $file->unique_id) abort(403);
        }
    }

    /**
     * Call and download file
     *
     * @param $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function download_file($file)
    {
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
     * @param $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function thumbnail_file($file)
    {
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
}
