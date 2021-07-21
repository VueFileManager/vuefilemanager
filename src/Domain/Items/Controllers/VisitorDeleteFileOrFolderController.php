<?php
namespace Domain\Items\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\Items\Actions\DeleteFileOrFolderAction;

/**
 * Delete item for guest user with edit permission
 */
class VisitorDeleteFileOrFolderController extends Controller
{
    public function __construct(
        private HelperService $helper,
        private DeleteFileOrFolderAction $deleteFileOrFolder,
    ) {
    }

    public function __invoke(
        DeleteItemRequest $request,
        Share $shared,
    ): Response {
        abort_if(
            is_demo_account($shared->user->email),
            204,
            'Done.'
        );

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        foreach ($request->input('items') as $file) {
            // Get file|folder item
            $item = get_item($file['type'], $file['id']);

            // Check access to requested item
            if ($file['type'] === 'folder') {
                $this->helper->check_item_access($item->id, $shared);
            } else {
                $this->helper->check_item_access($item->folder_id, $shared);
            }

            // Delete item
            ($this->deleteFileOrFolder)($file, $file['id'], $shared);
        }

        return response('Done', 204);
    }
}
