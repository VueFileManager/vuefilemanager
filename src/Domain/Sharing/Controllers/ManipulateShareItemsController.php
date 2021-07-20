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
use Support\Services\FileManagerService;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\Items\Requests\RenameItemRequest;
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
}
