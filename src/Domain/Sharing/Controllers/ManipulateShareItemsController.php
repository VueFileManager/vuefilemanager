<?php
namespace Domain\Sharing\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Support\Demo\Actions\DemoService;
use Illuminate\Database\Eloquent\Model;
use Domain\Files\Requests\UploadRequest;
use Support\Services\FileManagerService;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Zipping\Actions\ZipFilesAction;
use Domain\Zipping\Actions\ZipFolderAction;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\Items\Requests\RenameItemRequest;
use Domain\Folders\Requests\CreateFolderRequest;
use Illuminate\Contracts\Routing\ResponseFactory;

class ManipulateShareItemsController extends Controller
{
    public function __construct(
        private FileManagerService $filemanager,
        private HelperService $helper,
        private DemoService $demo,
    ) {
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
        if (is_demo_account($shared->user->email)) {
            return $this->demo->create_folder($request);
        }

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

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
        if (is_demo_account($shared->user->email)) {
            return $this->demo->rename_item($request, $id);
        }

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

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
            $this->filemanager->edit_folder_properties($request, $id);
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
        abort_if(is_demo_account($shared->user->email), 204, 'Done.');

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

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
        if (is_demo_account($shared->user->email)) {
            return $this->demo->upload($request);
        }

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

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
        abort_if(is_demo_account($shared->user->email), 204, 'Done.');

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        foreach ($request->items as $item) {
            if ($item['type'] === 'folder') {
                $this->helper->check_item_access([
                    $request->to_id, $item['id'],
                ], $shared);
            }

            if ($item['type'] !== 'folder') {
                $file = File::where('id', $item['id'])
                    ->where('user_id', $shared->user_id)
                    ->firstOrFail();

                $this->helper->check_item_access([
                    $request->to_id, $file->folder_id,
                ], $shared);
            }
        }

        $this->filemanager->move($request, $request->to_id);

        return response('Done!', 204);
    }

    /**
     * Guest download folder via zip
     */
    public function zip_folder(
        string $id,
        Share $shared,
        ZipFolderAction $zipFolder,
    ): Response {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check access to requested folder
        $this->helper->check_item_access($id, $shared);

        // Get folder
        $folder = Folder::whereUserId($shared->user_id)
            ->where('id', $id);

        if (! $folder->exists()) {
            abort(404, 'Requested folder doesn\'t exists.');
        }

        $zip = ($zipFolder)($id, $shared);

        // Get file
        return response([
            'url' => route('zip_public', [
                'id'    => $zip->id,
                'token' => $shared->token,
            ]),
            'name' => $zip->basename,
        ], 201);
    }

    /**
     * Guest download multiple files via zip
     */
    public function zip_multiple_files(
        Request $request,
        Share $shared,
        ZipFilesAction $zipFiles,
    ): Response {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

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

        $zip = ($zipFiles)($files, $shared);

        // Get file
        return response([
            'url' => route('zip_public', [
                'id'    => $zip->id,
                'token' => $shared->token,
            ]),
            'name' => $zip->basename,
        ], 201);
    }
}
