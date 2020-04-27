<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\AuthenticateShareRequest;
use App\Http\Resources\ShareResource;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
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
     * Show page index and delete access_token & shared_token cookie
     *
     * @return Factory|\Illuminate\View\View
     */
    public function index($token)
    {
        // Get shared token
        $shared = Share::where(DB::raw('BINARY `token`'), $token)
            ->firstOrFail();

        // Delete old access_token if exist
        Cookie::queue('access_token', '', -1);

        // Set cookies
        if ($shared->protected) {

            // Set shared token
            Cookie::queue('shared_token', $token, 43200);
        }

        // Return page index
        return view("index");
    }

    /**
     * Check Password for protected item
     *
     * @param AuthenticateShareRequest $request
     * @param $token
     * @return array
     */
    public function authenticate(AuthenticateShareRequest $request, $token)
    {
        // Get sharing record
        $shared = Share::where(DB::raw('BINARY `token`'), $token)->firstOrFail();

        // Check password
        if (!Hash::check($request->password, $shared->password)) {

            abort(401, 'Sorry, your password is incorrect.');
        }

        // Get owner of shared content
        $user = User::find($shared->user_id);

        // Define scope
        $scope = !is_null($shared->permission) ? $shared->permission : 'visitor';

        // Generate token for visitor/editor
        $access_token = $user->createToken('access_token', [$scope])->accessToken;

        // Return authorize token with shared options
        return response(new ShareResource($shared), 200)
            ->cookie('shared_token', $shared->token, 43200)
            ->cookie('access_token', $access_token, 43200);
    }

    /**
     * Browse private folders
     *
     * @param Request $request
     * @param $unique_id
     * @return Collection
     */
    public function get_private_folders(Request $request, $unique_id)
    {
        // Get sharing record
        $shared = Share::where('token', $request->cookie('shared_token'))->firstOrFail();

        // Check if user can get directory
        $this->check_folder_access($unique_id, $shared);

        // Get files and folders
        list($folders, $files) = $this->get_items($unique_id, $shared);

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Browse public folders
     *
     * @param $unique_id
     * @return Collection
     */
    public function get_public_folders($unique_id, $token)
    {
        // Get sharing record
        $shared = Share::where(DB::raw('BINARY `token`'), $token)->firstOrFail();

        // Abort if folder is protected
        if ($shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Check if user can get directory
        $this->check_folder_access($unique_id, $shared);

        // Get files and folders
        list($folders, $files) = $this->get_items($unique_id, $shared);

        // Set thumbnail links for public files
        $files->map(function ($item) use ($token) {
            $item->setPublicUrl($token);
        });

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
        $shared = Share::where(DB::raw('BINARY `token`'), $token)->firstOrFail();

        // Abort if file is protected
        if ($shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Get file
        $file = FileManagerFile::where('user_id', $shared->user_id)
            ->where('unique_id', $shared->item_id)
            ->firstOrFail(['name', 'basename', 'thumbnail', 'type', 'filesize', 'mimetype']);

        // Set urls
        $file->setPublicUrl($token);

        // Return record
        return $file;
    }

    /**
     * Get shared private file record
     *
     * @param $token
     * @return mixed
     */
    public function file_private(Request $request)
    {
        // Get sharing record
        $shared = Share::where('token', $request->cookie('shared_token'))->firstOrFail();

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
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Check user access
        if (!in_array($unique_id, $accessible_folder_ids)) abort(401);
    }

    /**
     * Get folders and files
     *
     * @param $unique_id
     * @param $shared
     * @return array
     */
    private function get_items($unique_id, $shared): array
    {
        $folders = FileManagerFolder::where('user_id', $shared->user_id)
            ->where('parent_id', $unique_id)
            ->get();

        $files = FileManagerFile::where('user_id', $shared->user_id)
            ->where('folder_id', $unique_id)
            ->get();

        return [$folders, $files];
    }
}
