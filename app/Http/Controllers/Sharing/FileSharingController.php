<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\FileManagerFolder;
use App\FileManagerFile;
use App\User;
use App\Share;

class FileSharingController extends Controller
{
    /**
     * Get shared record
     *
     * @param Request $request
     * @return mixed
     */
    public function index($token)
    {
        // Get sharing record
        return Share::where('token', $token)
            ->firstOrFail(['token', 'item_id', 'type', 'permission', 'protected']);
    }

    /**
     * Check Password for protected item
     *
     * @param Request $request
     * @param $token
     * @return array
     */
    public function authenticate(Request $request, $token)
    {
        // TODO: validacia

        // Get sharing record
        $shared = Share::where('token', $token)->firstOrFail();

        // Check password
        if (!Hash::check($request->password, $shared->password)) {

            abort(401, 'Sorry, your password is incorrect.');
        }

        // Get owner of shared content
        $user = User::find($shared->user_id);

        // Define scope
        $scope = !is_null($shared->permission) ? $shared->permission : 'visitor';

        // Generate token for visitor/editor
        $token = $user->createToken('token', [$scope])->accessToken;

        // Return authorize token with shared options
        return response(Arr::except($shared, ['password', 'user_id', 'updated_at', 'created_at']), 200)
            ->cookie('shared_token', $shared->token, 43200)
            ->cookie('token', $token, 43200);
    }

    /**
     * Browse private folders
     *
     * @param Request $request
     * @param $unique_id
     * @return Collection
     */
    public function browse_private(Request $request, $unique_id)
    {
        // Check if token exist
        if (!$request->has('token'))
            abort(404, "Sorry, you don't request any content");

        // Get sharing record
        $shared = Share::where('token', $request->token)->firstOrFail();

        // Check directory authentication
        $this->check_authenticated_access($request);

        // Check if user can get directory
        $this->check_folder_access($unique_id, $shared);

        // Get folders and files
        $folders = FileManagerFolder::where('user_id', $shared->user_id)
            ->where('parent_id', $unique_id)
            ->get();

        $files = FileManagerFile::where('user_id', $shared->user_id)
            ->where('folder_id', $unique_id)
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Browse public folders
     *
     * @param Request $request
     * @param $unique_id
     * @return Collection
     */
    public function browse_public(Request $request, $unique_id)
    {

        // Check if token exist
        if (!$request->has('token'))
            abort(404, "Sorry, you don't request any content");

        // Get sharing record
        $shared = Share::where('token', $request->token)->firstOrFail();

        // Abort if folder is protected
        if ($shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Check if user can get directory
        $this->check_folder_access($unique_id, $shared);

        // Get folders and files
        $folders = FileManagerFolder::where('user_id', $shared->user_id)
            ->where('parent_id', $unique_id)
            ->get();

        $files = FileManagerFile::where('user_id', $shared->user_id)
            ->where('folder_id', $unique_id)
            ->get();

        // Add shared token to file
        /*if ($shared->protected) {

            $files->map(function ($file) use ($shared) {
                //$file->thumbnail = $file->getOriginal('thumbnail') . '?token=' . $shared->token;

                $file->thumbnail = route('thumbnail-public', ['name' => $file->getOriginal('thumbnail')]);
            });
        }*/

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Get shared public file record
     *
     * @param $token
     * @return mixed
     */
    public function file_public($token)
    {
        // Get sharing record
        $shared = Share::where('token', $token)->firstOrFail();

        // Abort if file is protected
        if ($shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Return record
        return FileManagerFile::where('user_id', $shared->user_id)
            ->where('unique_id', $shared->item_id)
            ->firstOrFail(['name', 'basename', 'thumbnail', 'type', 'filesize', 'mimetype']);
    }

    /**
     * Get shared private file record
     *
     * @param $token
     * @return mixed
     */
    public function file_private(Request $request, $token)
    {
        // Get sharing record
        $shared = Share::where('token', $token)->firstOrFail();

        // Check file authentication
        $this->check_authenticated_access($request);

        // Return record
        return FileManagerFile::where('user_id', $shared->user_id)
            ->where('unique_id', $shared->item_id)
            ->firstOrFail(['name', 'basename', 'thumbnail', 'type', 'filesize', 'mimetype']);
    }

    /**
     * Check if user has access to requested folder
     *
     * @param $folder_unique_id
     * @param $shared
     */
    protected function check_folder_access($unique_id, $shared): void
    {
        // Get all children folders
        $foldersIds = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get all authorized parent folders by shared folder as root of tree
        $authorized_parent_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Check user access
        if (!in_array($unique_id, $authorized_parent_folder_ids)) abort(401);
    }

    /**
     * @param Request $request
     */
    protected function check_authenticated_access(Request $request): void
    {
        // Check directory permission
        if ($request->cookie('shared_token') !== $request->token)
            abort(401, "Sorry, you don't have permission");
    }
}
