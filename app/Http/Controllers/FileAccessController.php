<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\FileManagerFile;
use Response;

class FileAccessController extends Controller
{
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

        /*        if ($request->has('token')) {

                    // Get sharing record
                    $shared = Share::where('token', $request->token)->firstOrFail();

                    // Get all children folders
                    $foldersIds = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
                        ->where('user_id', $user_id)
                        ->where('parent_id', $shared->item_id)
                        ->get();

                    // Get all authorized parent folders by shared folder as root of tree
                    $authorized_parent_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

                    // Check user access
                    if ( ! in_array($file->folder_id, $authorized_parent_folder_ids)) abort(401);
                }*/

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
