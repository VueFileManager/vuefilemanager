<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Share\AuthenticateShareRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\ShareResource;
use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Services\HelperService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BrowseShareController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Show page index and delete access_token & shared_token cookie
     * @param Share $shared
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function index(Share $shared)
    {
        // Delete share_session if exist
        if ($shared->is_protected) {
            cookie()->queue('share_session', '', -1);
        }

        // Check if shared is image file and then show it
        if ($shared->type === 'file' && !$shared->is_protected) {

            $image = File::whereUserId($shared->user_id)
                ->whereType('image')
                ->whereId($shared->item_id)
                ->first();

            if ($image) {

                // Store user download size
                $shared
                    ->user
                    ->record_download(
                        (int)$image->getRawOriginal('filesize')
                    );

                return $this->get_single_image($image, $shared->user_id);
            }
        }

        return view("index")
            ->with('installation', 'setup-done')
            ->with('settings', get_settings_in_json() ?? null);
    }

    /**
     * Check Password for protected item
     *
     * @param AuthenticateShareRequest $request
     * @param Share $shared
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function authenticate(AuthenticateShareRequest $request, Share $shared)
    {
        // Check password
        if (Hash::check($request->password, $shared->password)) {

            $cookie = json_encode([
                'token'         => $shared->token,
                'authenticated' => true,
            ]);

            // Return authorize token with shared options
            return response(new ShareResource($shared), 200)
                ->cookie('share_session', $cookie, 43200);
        }

        abort(401, __t('incorrect_password'));
    }

    /**
     * Browse shared folder
     *
     * @param $id
     * @param Share $shared
     * @return Collection
     */
    public function browse_folder($id, Share $shared)
    {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check if user can get directory
        $this->helper->check_item_access($id, $shared);

        // Get files and folders
        list($folders, $files) = $this->helper->get_items_under_shared_by_folder_id($id, $shared);

        // Set thumbnail links for public files
        $files->map(function ($file) use ($shared) {
            $file->setPublicUrl($shared->token);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }

    /**
     * Search shared files
     *
     * @param Request $request
     * @param Share $shared
     * @return Collection
     */
    public function search(Request $request, Share $shared)
    {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        $query = remove_accents(
            $request->input('query')
        );

        // Search files id db
        $searched_files = File::search($query)
            ->where('user_id', $shared->user_id)
            ->get();
        $searched_folders = Folder::search($query)
            ->where('user_id', $shared->user_id)
            ->get();

        // Get all children content
        $foldersIds = Folder::with('folders:id,parent_id,id,name')
            ->where('user_id', $shared->user_id)
            ->where('parent_id', $shared->item_id)
            ->get();

        // Get accessible folders
        $accessible_folder_ids = Arr::flatten([filter_folders_ids($foldersIds), $shared->item_id]);

        // Filter files
        $files = $searched_files->filter(function ($file) use ($accessible_folder_ids, $shared) {

            // Set public urls
            $file->setPublicUrl($shared->token);

            // check if item is in accessible folders
            return in_array($file->folder_id, $accessible_folder_ids);
        });

        // Filter folders
        $folders = $searched_folders->filter(function ($folder) use ($accessible_folder_ids) {

            // check if item is in accessible folders
            return in_array($folder->id, $accessible_folder_ids);
        });

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }

    /**
     * Get navigation tree of shared folder
     *
     * @param Share $shared
     * @return array
     */
    public function navigation_tree(Share $shared)
    {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check if user can get directory
        $this->helper->check_item_access($shared->item_id, $shared);

        // Get folders
        $folders = Folder::with('folders:id,parent_id,name')
            ->whereParentId($shared->item_id)
            ->whereUserId($shared->user_id)
            ->sortable()
            ->get(['id', 'parent_id', 'id', 'name']);

        return [
            [
                'id'       => $shared->item_id,
                'name'     => __t('home'),
                'location' => 'public',
                'folders'  => $folders,
            ]
        ];
    }

    /**
     * Get shared file record
     *
     * @param Share $shared
     * @return mixed
     */
    public function get_single_file(Share $shared)
    {
        // Check ability to access protected share files
        $this->helper->check_protected_share_record($shared);

        // Get file
        $file = File::whereUserId($shared->user_id)
            ->whereId($shared->item_id)
            ->firstOrFail();

        // Set access urls
        $file->setPublicUrl($shared->token);

        return response(new FileResource($file), 200);
    }

    /**
     * Get image from storage and show it
     *
     * @param $file
     * @param $user_id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function get_single_image($file, $user_id)
    {
        // Format pretty filename
        $file_pretty_name = $file->name . '.' . $file->mimetype;

        // Get file path
        $path = "/files/$user_id/$file->basename";

        // Check if file exist
        if (!Storage::exists($path)) abort(404);

        return Storage::response($path, $file_pretty_name, [
            "Content-Type"   => Storage::mimeType($path),
            "Content-Length" => Storage::size($path),
            "Accept-Ranges"  => "bytes",
            "Content-Range"  => "bytes 0-600/" . Storage::size($path),
        ]);
    }
}
