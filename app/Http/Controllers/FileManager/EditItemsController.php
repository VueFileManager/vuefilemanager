<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Requests\FileFunctions\CreateFolderRequest;
use App\Http\Requests\FileFunctions\DeleteItemRequest;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\Http\Requests\FileFunctions\MoveItemRequest;
use App\Http\Requests\FileFunctions\UploadRequest;
use App\Http\Tools\Demo;
use App\Services\FileManagerService;
use App\Services\HelperService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\File;
use Exception;


class EditItemsController extends Controller
{
    private $filemanager;
    private $helper;

    public function __construct()
    {
        $this->filemanager = resolve(FileManagerService::class);
        $this->helper = resolve(HelperService::class);
    }

    /**
     * Create new folder for authenticated master|editor user
     *
     * @param CreateFolderRequest $request
     * @return array
     * @throws Exception
     */
    public function create_folder(CreateFolderRequest $request)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::create_folder($request);
        }

        // Check permission to create folder for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to requested directory
            $this->helper->check_item_access($request->parent_id, $shared);
        }

        // Create new folder
        return $this->filemanager->create_folder($request);
    }

    /**
     * Rename item for authenticated master|editor user
     *
     * @param RenameItemRequest $request
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function rename_item(RenameItemRequest $request, $id)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::rename_item($request, $id);
        }

        // Check permission to rename item for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Get file|folder item
            $item = get_item($request->type, $id);

            // Check access to requested directory
            if ($request->type === 'folder') {
                $this->helper->check_item_access($item->id, $shared);
            } else {
                $this->helper->check_item_access($item->folder_id, $shared);
            }
        }

        // If request have a change folder icon values set the folder icon
        if ($request->type === 'folder' && ($request->filled('emoji') || $request->filled('color'))) {
            $this->filemanager->set_folder_icon($request, $id);
        }

        // Rename Item
        return $this->filemanager->rename_item($request, $id);
    }

    /**
     * Delete item for authenticated master|editor user
     *
     * @param DeleteItemRequest $request
     * @param $id
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function delete_item(DeleteItemRequest $request)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::response_204();
        }

        foreach ($request->input('items') as $item) {

            // Check permission to delete item for authenticated editor 
            if ($request->user()->tokenCan('editor')) {

                // Prevent force delete for non-master users
                if ($item['force_delete']) abort('401');

                // check if shared_token cookie exist
                if (!$request->hasCookie('shared_token')) abort('401');

                // Get shared token
                $shared = get_shared($request->cookie('shared_token'));

                // Get file|folder item
                $item = get_item($item['type'], $item['id']);

                // Check access to requested directory
                if ($item['type'] === 'folder') {
                    $this->helper->check_item_access($item->id, $shared);
                } else {
                    $this->helper->check_item_access($item->folder_id, $shared);
                }
            }

            // Delete item
            $this->filemanager->delete_item($item, $item['id']);
        }

        return response(null, 204);
    }

    /**
     * Upload file for authenticated master|editor user
     *
     * @param UploadRequest $request
     * @return File|Model
     * @throws Exception
     */
    public function upload(UploadRequest $request)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::upload($request);
        }

        // Check permission to upload for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to requested directory
            $this->helper->check_item_access($request->parent_id, $shared);
        }

        // Return new uploaded file
        return $this->filemanager->upload($request);
    }

    /**
     * Move item for authenticated master|editor user
     *
     * @param MoveItemRequest $request
     * @param $id
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function move(MoveItemRequest $request)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::response_204();
        }

        $to_id = $request->input('to_id');

        // Check permission to upload for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to requested directory
            $this->helper->check_item_access($to_id, $shared);
        }

        // Move item
        $this->filemanager->move($request, $to_id);

        return response('Done!', 204);
    }

    /**
     * User download folder via zip
     *
     * @param $id
     * @return string
     */
    public function zip_folder(Request $request, $id)
    {
        // Get user id
        $user_id = Auth::id();

        // Check permission to download for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to requested directory
            $this->helper->check_item_access($id, $shared);
        }

        // Get folder
        $folder = Folder::whereUserId($user_id)
            ->where('id', $id);

        if (!$folder->exists()) {
            abort(404, 'Requested folder doesn\'t exists.');
        }

        $zip = $this->filemanager->zip_folder($id);

        // Get file
        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }

    /**
     * User download multiple files via zip
     *
     * @param Request $request
     * @return string
     */
    public function zip_multiple_files(Request $request)
    {
        // Check permission to upload for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            $file_parent_folders = File::whereUserId(Auth::id())
                ->whereIn('id', $request->input('files'))
                ->get()
                ->pluck('folder_id')
                ->toArray();

            // Check access to requested directory
            $this->helper->check_item_access($file_parent_folders, $shared);
        }

        // Get requested files
        $files = File::whereUserId(Auth::id())
            ->whereIn('id', $request->input('items'))
            ->get();

        $zip = $this->filemanager->zip_files($files);

        // Get file
        return response([
            'url'  => route('zip', $zip->id),
            'name' => $zip->basename,
        ], 201);
    }
}