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
use Illuminate\Support\Facades\Storage;
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
        $path = '/avatars/' . $basename;

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        // Return avatar
        return Storage::download($path, $basename);
    }

    /**
     * Get system image
     *
     * @param $basename
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get_system_image($basename)
    {
        // Get file path
        $path = '/system/' . $basename;

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        // Return avatar
        return Storage::download($path, $basename);
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
        if (!$request->user()->tokenCan('master')) {

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
        if ((int)$shared->protected) {
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
        if (!$request->user()->tokenCan('master')) {
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
        if ((int)$shared->protected) {
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
        $file_pretty_name = get_pretty_name($file->basename, $file->name, $file->mimetype);

        // Get file path
        $path = '/file-manager/' . $file->basename;

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        $header = [
            "Content-Type"   => Storage::mimeType($path),
            "Content-Length" => Storage::size($path),
            "Accept-Ranges"  => "bytes",
            "Content-Range"  => "bytes 0-600/" . Storage::size($path),
        ];

        // Get file
        return Storage::download($path, $file_pretty_name, $header);
    }

    /**
     * @param $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function thumbnail_file($file)
    {
        // Get file path
        $path = '/file-manager/' . $file->getRawOriginal('thumbnail');

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        // Return image thumbnail
        return Storage::download($path, $file->getRawOriginal('thumbnail'));
    }
}
