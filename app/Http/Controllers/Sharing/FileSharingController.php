<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\AuthenticateShareRequest;
use App\Http\Resources\ShareResource;
use App\Http\Tools\Guardian;
use App\Setting;
use http\Env\Response;
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
use Illuminate\Support\Facades\Storage;

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
        $shared = Share::where(\DB::raw('BINARY `token`'), $token)
            ->first();

        if (! $shared) {
            return view("index");
        }

        // Delete old access_token if exist
        Cookie::queue('shared_access_token', '', -1);

        // Set cookies
        if ((int) $shared->protected) {

            // Set shared token
            Cookie::queue('shared_token', $token, 43200);
        }

        // Check if shared is image file and then show it
        if ($shared->type === 'file' && ! (int) $shared->protected) {

            $image = FileManagerFile::where('user_id', $shared->user_id)
                ->where('type', 'image')
                ->where('unique_id', $shared->item_id)
                ->first();

            if ($image) {

                // Store user download size
                User::find($shared->user_id)->record_download((int) $image->getRawOriginal('filesize'));

                return $this->show_image($image);
            }
        }

        // Get all settings
        $settings = Setting::all();

        // Return page index
        return view("index")
            ->with('settings', $settings ? json_decode($settings->pluck('value', 'name')->toJson()) : null);
    }

    /**
     * Get image from storage and show it
     *
     * @param $file
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function show_image($file)
    {
        // Format pretty filename
        $file_pretty_name = $file->name . '.' . $file->mimetype;

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
        return Storage::response($path, $file_pretty_name, $header);
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

            abort(401, __t('incorrect_password'));
        }

        // Get owner of shared content
        $user = User::find($shared->user_id);

        // Define scope
        $scope = !is_null($shared->permission) ? $shared->permission : 'visitor';

        // Generate token for visitor/editor
        $access_token = $user->createToken('shared_access_token', [$scope])->accessToken;

        // Return authorize token with shared options
        return response(new ShareResource($shared), 200)
            ->cookie('shared_token', $shared->token, 43200)
            ->cookie('shared_access_token', $access_token, 43200);
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
        Guardian::check_item_access($unique_id, $shared);

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
        if ((int) $shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Check if user can get directory
        Guardian::check_item_access($unique_id, $shared);

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
        if ((int) $shared->protected) {
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
     * Get navigation tree
     *
     * @param Request $request
     * @return array
     */
    public function get_private_navigation_tree(Request $request)
    {
        // Get sharing record
        $shared = get_shared($request->cookie('shared_token'));

        // Check if user can get directory
        Guardian::check_item_access($shared->item_id, $shared);

        // Get folders
        $folders = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('parent_id', $shared->item_id)
            ->where('user_id', $shared->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'unique_id', 'name']);

        // Return folder tree
        return [
            [
                'unique_id' => $shared->item_id,
                'name'      => __t('home'),
                'location'  => 'public',
                'folders'   => $folders,
            ]
        ];
    }

    /**
     * Get navigation tree
     *
     * @return array
     */
    public function get_public_navigation_tree($token)
    {
        // Get sharing record
        $shared = Share::where('token', $token)->firstOrFail();

        // Check if user can get directory
        Guardian::check_item_access($shared->item_id, $shared);

        // Get folders
        $folders = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('parent_id', $shared->item_id)
            ->where('user_id', $shared->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'unique_id', 'name']);

        // Return folder tree
        return [
            [
                'unique_id' => $shared->item_id,
                'name'      => __t('home'),
                'location'  => 'public',
                'folders'   => $folders,
            ]
        ];
    }

    /**
     * Search private files
     *
     * @param Request $request
     * @param $token
     * @return Collection
     */
    public function search_private(Request $request)
    {
        // Get shared
        $shared = get_shared($request->cookie('shared_token'));

        // Search files id db
        $searched_files = FileManagerFile::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();
        $searched_folders = FileManagerFolder::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();

        // Get all children content
        $foldersIds = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Filter files to only accessible files
        $files = $searched_files->filter(function ($file) use ($accessible_folder_ids) {
            return in_array($file->folder_id, $accessible_folder_ids);
        });

        // Filter folders to only accessible folders
        $folders = $searched_folders->filter(function ($folder) use ($accessible_folder_ids) {
            return in_array($folder->unique_id, $accessible_folder_ids);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
    }

    /**
     * Search public files
     *
     * @param Request $request
     * @param $token
     * @return Collection
     */
    public function search_public(Request $request, $token)
    {
        // Get shared
        $shared = get_shared($token);

        // Abort if folder is protected
        if ((int) $shared->protected) {
            abort(403, "Sorry, you don't have permission");
        }

        // Search files id db
        $searched_files = FileManagerFile::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();
        $searched_folders = FileManagerFolder::search($request->input('query'))
            ->where('user_id', $shared->user_id)
            ->get();

        // Get all children content
        $foldersIds = FileManagerFolder::with('folders:id,parent_id,unique_id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Filter files
        $files = $searched_files->filter(function ($file) use ($accessible_folder_ids, $token) {

            // Set public urls
            $file->setPublicUrl($token);

            // check if item is in accessible folders
            return in_array($file->folder_id, $accessible_folder_ids);
        });

        // Filter folders
        $folders = $searched_folders->filter(function ($folder) use ($accessible_folder_ids) {

            // check if item is in accessible folders
            return in_array($folder->unique_id, $accessible_folder_ids);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])->collapse();
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
            ->sortable()
            ->get();

        $files = FileManagerFile::where('user_id', $shared->user_id)
            ->where('folder_id', $unique_id)
            ->sortable()
            ->get();

        return [$folders, $files];
    }
}
