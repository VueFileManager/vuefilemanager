<?php

namespace App\Http\Controllers\Sharing;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileFunctions\CreateFolderRequest;
use App\Http\Requests\FileFunctions\DeleteItemRequest;
use App\Http\Requests\FileFunctions\MoveItemRequest;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\Http\Requests\FileFunctions\UploadRequest;
use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Services\DemoService;
use App\Services\FileManagerService;
use App\Services\HelperService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditShareItemsController extends Controller
{
    private $filemanager;
    private $helper;

    public function __construct()
    {
        $this->filemanager = resolve(FileManagerService::class);
        $this->helper = resolve(HelperService::class);
        $this->demo = resolve(DemoService::class);
    }

    /**
     * Create new folder for guest user with edit permission
     *
     * @param CreateFolderRequest $request
     * @param Share $shared
     * @return array|\Illuminate\Contracts\Foundation\Application|ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function create_folder(CreateFolderRequest $request, Share $shared)
    {
        if (is_demo($shared->user_id)) {
            return $this->demo->create_folder($request);
        }

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Check access to requested directory
        $this->helper->check_item_access($request->parent_id, $shared);

        // Create folder
        $folder = $this->filemanager->create_folder($request, $shared);

        return response($folder, 201);
    }

    /**
     * Rename item for guest user with edit permission
     *
     * @param RenameItemRequest $request
     * @param $id
     * @param Share $shared
     * @return mixed
     * @throws \Exception
     */
    public function rename_item(RenameItemRequest $request, $id, Share $shared)
    {
        // Demo preview
        if (is_demo($shared->user_id)) {
            return $this->demo->rename_item($request, $id);
        }

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Get file|folder item
        $item = get_item($request->type, $id);

        // Check access to requested item
        if ($request->type === 'folder') {
            $this->helper->check_item_access($item->id, $shared);
        } else {
            $this->helper->check_item_access($item->folder_id, $shared);
        }

        // If request have a change folder icon values set the folder icon
        if ($request->type === 'folder' && $request->filled('icon')) {
            $this->filemanager->set_folder_icon($request, $id);
        }

        // Rename item
        $item = $this->filemanager->rename_item($request, $id, $shared);

        // Set public url
        if ($item->type !== 'folder') {
            $item->setPublicUrl($shared->token);
        }

        return response($item, 201);
    }

    /**
     * Delete item for guest user with edit permission
     *
     * @param DeleteItemRequest $request
     * @param Share $shared
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function delete_item(DeleteItemRequest $request, Share $shared)
    {
        // Demo preview
        if (is_demo($shared->user_id)) {
            return $this->demo->response_with_no_content();
        }

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        foreach ($request->items as $file) {

            // Get file|folder item
            $item = get_item($file['type'], $file['id']);

            // Check access to requested item
            if ($file['type'] === 'folder') {
                $this->helper->check_item_access($item->id, $shared);
            } else {
                $this->helper->check_item_access($item->folder_id, $shared);
            }

            // Delete item
            $this->filemanager->delete_item($file, $file['id'], $shared);
        }

        return response('Done', 204);
    }

    /**
     * Delete file for guest user with edit permission
     *
     * @param UploadRequest $request
     * @param Share $shared
     * @return File|\Illuminate\Contracts\Foundation\Application|ResponseFactory|Model|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function upload(UploadRequest $request, Share $shared)
    {
        // Demo preview
        if (is_demo($shared->user_id)) {
            return $this->demo->upload($request);
        }

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Check access to requested directory
        $this->helper->check_item_access($request->folder_id, $shared);

        // Return new uploaded file
        $new_file = $this->filemanager->upload($request, $shared);

        // Set public access url
        $new_file->setPublicUrl($shared->token);

        return response($new_file, 201);
    }

    /**
     * Move item for guest user with edit permission
     *
     * @param MoveItemRequest $request
     * @param Share $shared
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function move(MoveItemRequest $request, Share $shared)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return $this->demo->response_with_no_content();
        }

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        foreach ($request->items as $item) {

            if ($item['type'] === 'folder') {

                $this->helper->check_item_access([
                    $request->to_id, $item['id']
                ], $shared);
            }

            if ($item['type'] !== 'folder') {

                $file = File::where('id', $item['id'])
                    ->where('user_id', $shared->user_id)
                    ->firstOrFail();

                $this->helper->check_item_access([
                    $request->to_id, $file->folder_id
                ], $shared);
            }
        }

        $this->filemanager->move($request, $request->to_id);

        return response('Done!', 204);
    }

    /**
     * Guest download folder via zip
     *
     * @param $id
     * @param Share $shared
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function zip_folder($id, Share $shared)
    {
        // Check access to requested folder
        $this->helper->check_item_access($id, $shared);

        // Get folder
        $folder = Folder::whereUserId($shared->user_id)
            ->where('id', $id);

        if (!$folder->exists()) {
            abort(404, 'Requested folder doesn\'t exists.');
        }

        $zip = $this->filemanager->zip_folder($id, $shared);

        // Get file
        return response([
            'url'  => route('zip_public', [
                'id'    => $zip->id,
                'token' => $shared->token,
            ]),
            'name' => $zip->basename,
        ], 201);
    }

    /**
     * Guest download multiple files via zip
     *
     * @param Request $request
     * @param Share $shared
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function zip_multiple_files(Request $request, Share $shared)
    {
        $file_parent_folders = File::whereUserId($shared->user_id)
            ->whereIn('id', $request->items)
            ->get()
            ->pluck('folder_id')
            ->toArray();

        // Check access to requested directory
        $this->helper->check_item_access($file_parent_folders, $shared);

        // Get requested files
        $files = File::whereUserId($shared->user_id)
            ->whereIn('id', $request->items)
            ->get();

        $zip = $this->filemanager->zip_files($files, $shared);

        // Get file
        return response([
            'url'  => route('zip_public', [
                'id'    => $zip->id,
                'token' => $shared->token,
            ]),
            'name' => $zip->basename,
        ], 201);
    }
}
