<?php

namespace App\Http\Controllers\FileFunctions;

use App\Http\Requests\FileFunctions\CreateFolderRequest;
use App\Http\Requests\FileFunctions\DeleteItemRequest;
use App\Http\Requests\FileFunctions\RenameItemRequest;
use App\Http\Requests\FileFunctions\MoveItemRequest;
use App\Http\Requests\FileFunctions\UploadRequest;
use App\Http\Tools\Demo;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Tools\Guardian;
use App\Http\Tools\Editor;
use App\FileManagerFile;
use Exception;


class EditItemsController extends Controller
{
    /**
     * Create new folder for authenticated master|editor user
     *
     * @param CreateFolderRequest $request
     * @return array
     * @throws Exception
     */
    public function user_create_folder(CreateFolderRequest $request)
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
            Guardian::check_item_access($request->parent_id, $shared);
        }

        // Create new folder
        return Editor::create_folder($request);
    }

    /**
     * Create new folder for guest user with edit permission
     *
     * @param CreateFolderRequest $request
     * @param $token
     * @return array
     * @throws Exception
     */
    public function guest_create_folder(CreateFolderRequest $request, $token)
    {
        // Get shared record
        $shared = get_shared($token);

        if (is_demo($shared->user_id)) {
            return Demo::create_folder($request);
        }

        // Check shared permission
        if (!is_editor($shared)) abort(403);

        // Check access to requested directory
        Guardian::check_item_access($request->parent_id, $shared);

        // Create folder
        return Editor::create_folder($request, $shared);
    }

    /**
     * Rename item for authenticated master|editor user
     *
     * @param RenameItemRequest $request
     * @param $unique_id
     * @return mixed
     * @throws Exception
     */
    public function user_rename_item(RenameItemRequest $request, $unique_id)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::rename_item($request, $unique_id);
        }

        // Check permission to rename item for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Get file|folder item
            $item = get_item($request->type, $unique_id, Auth::id());

            // Check access to requested directory
            if ($request->type === 'folder') {
                Guardian::check_item_access($item->unique_id, $shared);
            } else {
                Guardian::check_item_access($item->folder_id, $shared);
            }
        }

        // Rename Item
        return Editor::rename_item($request, $unique_id);
    }

    /**
     * Rename item for guest user with edit permission
     *
     * @param RenameItemRequest $request
     * @param $unique_id
     * @param $token
     * @return mixed
     * @throws Exception
     */
    public function guest_rename_item(RenameItemRequest $request, $unique_id, $token)
    {
        // Get shared record
        $shared = get_shared($token);

        // Demo preview
        if (is_demo($shared->user_id)) {
            return Demo::rename_item($request, $unique_id);
        }

        // Check shared permission
        if (!is_editor($shared)) abort(403);

        // Get file|folder item
        $item = get_item($request->type, $unique_id, $shared->user_id);

        // Check access to requested item
        if ($request->type === 'folder') {
            Guardian::check_item_access($item->unique_id, $shared);
        } else {
            Guardian::check_item_access($item->folder_id, $shared);
        }

        // Rename item
        $item = Editor::rename_item($request, $unique_id, $shared);

        // Set public url
        if ($item->type !== 'folder') {
            $item->setPublicUrl($token);
        }

        return $item;
    }

    /**
     * Delete item for authenticated master|editor user
     *
     * @param DeleteItemRequest $request
     * @param $unique_id
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function user_delete_item(DeleteItemRequest $request, $unique_id)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::response_204();
        }

        // Check permission to delete item for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // Prevent force delete for non-master users
            if ($request->force_delete) abort('401');

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Get file|folder item
            $item = get_item($request->type, $unique_id, Auth::id());

            // Check access to requested directory
            if ($request->type === 'folder') {
                Guardian::check_item_access($item->unique_id, $shared);
            } else {
                Guardian::check_item_access($item->folder_id, $shared);
            }
        }

        // Delete item
        Editor::delete_item($request, $unique_id);

        // Return response
        return response(null, 204);
    }

    /**
     * Delete item for guest user with edit permission
     *
     * @param DeleteItemRequest $request
     * @param $unique_id
     * @param $token
     * @return ResponseFactory|\Illuminate\Http\Response
     * @throws Exception
     */
    public function guest_delete_item(DeleteItemRequest $request, $unique_id, $token)
    {
        // Get shared record
        $shared = get_shared($token);

        // Demo preview
        if (is_demo($shared->user_id)) {
            return Demo::response_204();
        }

        // Check shared permission
        if (!is_editor($shared)) abort(403);

        // Get file|folder item
        $item = get_item($request->type, $unique_id, $shared->user_id);

        // Check access to requested item
        if ($request->type === 'folder') {
            Guardian::check_item_access($item->unique_id, $shared);
        } else {
            Guardian::check_item_access($item->folder_id, $shared);
        }

        // Delete item
        Editor::delete_item($request, $unique_id, $shared);

        // Return response
        return response(null, 204);
    }

    /**
     * Delete file for authenticated master|editor user
     *
     * @param UploadRequest $request
     * @return FileManagerFile|Model
     * @throws Exception
     */
    public function user_upload(UploadRequest $request)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::upload($request);
        }

        // Check if user can upload
        if (config('vuefilemanager.limit_storage_by_capacity') && user_storage_percentage() >= 100) {
            abort(423, 'You exceed your storage limit!');
        }

        // Check permission to upload for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to requested directory
            Guardian::check_item_access($request->parent_id, $shared);
        }

        // Return new uploaded file
        return Editor::upload($request);
    }

    /**
     * Delete file for guest user with edit permission
     *
     * @param UploadRequest $request
     * @param $token
     * @return FileManagerFile|Model
     * @throws Exception
     */
    public function guest_upload(UploadRequest $request, $token)
    {
        // Get shared record
        $shared = get_shared($token);

        // Demo preview
        if (is_demo($shared->user_id)) {
            return Demo::upload($request);
        }

        // Check shared permission
        if (!is_editor($shared)) abort(403);

        // Check access to requested directory
        Guardian::check_item_access($request->parent_id, $shared);

        // Return new uploaded file
        $new_file = Editor::upload($request, $shared);

        // Set public access url
        $new_file->setPublicUrl($token);

        return $new_file;
    }

    /**
     * Move item for authenticated master|editor user
     *
     * @param MoveItemRequest $request
     * @param $unique_id
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function user_move(MoveItemRequest $request, $unique_id)
    {
        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::response_204();
        }

        // Check permission to upload for authenticated editor
        if ($request->user()->tokenCan('editor')) {

            // check if shared_token cookie exist
            if (!$request->hasCookie('shared_token')) abort('401');

            // Get shared token
            $shared = get_shared($request->cookie('shared_token'));

            // Check access to requested directory
            Guardian::check_item_access($request->to_unique_id, $shared);
        }

        // Move item
        Editor::move($request, $unique_id);

        return response('Done!', 204);
    }

    /**
     * Move item for guest user with edit permission
     *
     * @param MoveItemRequest $request
     * @param $unique_id
     * @param $token
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function guest_move(MoveItemRequest $request, $unique_id, $token)
    {
        // Get shared record
        $shared = get_shared($token);

        // Demo preview
        if (is_demo(Auth::id())) {
            return Demo::response_204();
        }

        // Check shared permission
        if (!is_editor($shared)) abort(403);

        $moving_unique_id = $unique_id;

        if ($request->from_type !== 'folder') {
            $file = FileManagerFile::where('unique_id', $unique_id)
                ->where('user_id', $shared->user_id)
                ->firstOrFail();

            $moving_unique_id = $file->folder_id;
        }

        // Check access to requested item
        Guardian::check_item_access([
            $request->to_unique_id, $moving_unique_id
        ], $shared);

        // Move item
        Editor::move($request, $unique_id, $shared);

        return response('Done!', 204);
    }
}