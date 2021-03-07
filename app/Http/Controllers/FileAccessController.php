<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Http\Tools\Editor;
use App\Http\Tools\Guardian;
use App\Models\Share;
use App\Models\Zip;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\File as UserFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Madnest\Madzipper\Facades\Madzipper;
use Response;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        // Check if file exist
        if (!Storage::exists("/avatars/$basename")) {
            abort(404);
        }

        // Return avatar
        return Storage::download("/avatars/$basename", $basename);
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
        // Check if file exist
        if (!Storage::exists("/system/$basename")) {
            abort(404);
        }

        // Return avatar
        return Storage::download("/system/$basename", $basename);
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
        // Get file record
        $file = UserFile::withTrashed()
            ->where('user_id', Auth::id())
            ->where('basename', $filename)
            ->firstOrFail();

        // Check user permission
        /*if (!$request->user()->tokenCan('master')) {

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to file
            $this->check_file_access($shared, $file);
        }*/


        // Store user download size
        $request->user()->record_download(
            (int) $file->getRawOriginal('filesize')
        );

        return $this->download_file($file, Auth::id());
    }

    /**
     * Get generated zip for user
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get_zip($id)
    {
        $zip = Zip::whereId($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $disk = Storage::disk('local');

        return $disk->download("zip/$zip->basename", $zip->basename, [
            "Content-Type"        => 'application/zip',
            "Content-Length"      => $disk->size("zip/$zip->basename"),
            "Accept-Ranges"       => "bytes",
            "Content-Range"       => "bytes 0-600/" . $disk->size("zip/$zip->basename"),
            "Content-Disposition" => "attachment; filename=$zip->basename",
        ]);
    }

    /**
     * Get generated zip for guest
     *
     * @param $id
     * @param $token
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function get_zip_public($id, $token)
    {
        $zip = Zip::where('id', $id)
            ->where('shared_token', $token)
            ->first();

        $zip_path = 'zip/' . $zip->basename;

        $header = [
            "Content-Type"        => 'application/zip',
            "Content-Length"      => Storage::disk('local')->size($zip_path),
            "Accept-Ranges"       => "bytes",
            "Content-Range"       => "bytes 0-600/" . Storage::disk('local')->size($zip_path),
            "Content-Disposition" => "attachment; filename=" . $zip->basename,
        ];

        return Storage::disk('local')->download($zip_path, $zip->basename, $header);
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
        $file = File::where('user_id', $shared->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Check file access
        $this->check_file_access($shared, $file);

        // Store user download size
        User::find($shared->user_id)->record_download((int)$file->getRawOriginal('filesize'));

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
        $file = File::withTrashed()
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
        $file = File::where('user_id', $shared->user_id)
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
     * @param $user_id
     * @return mixed
     */
    private function download_file($file, $user_id)
    {
        // Get file path
        $path = "files/$user_id/$file->basename";

        // Check if file exist
        if (!Storage::exists($path)) {
            abort(404);
        }

        // Get pretty name
        $pretty_name = get_pretty_name($file->basename, $file->name, $file->mimetype);

        $headers = [
            "Accept-Ranges"       => "bytes",
            "Content-Type"        => Storage::mimeType($path),
            "Content-Length"      => Storage::size($path),
            "Content-Range"       => "bytes 0-600/" . Storage::size($path),
            "Content-Disposition" => "attachment; filename=$pretty_name",
        ];

        return response()
            ->download(Storage::path($path), $pretty_name, $headers);
    }

    /**
     * @param $file
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function thumbnail_file($file)
    {
        // Get file path
        $path = '/files/' . $file->getRawOriginal('thumbnail');

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        // Return image thumbnail
        return Storage::download($path, $file->getRawOriginal('thumbnail'));
    }
}
