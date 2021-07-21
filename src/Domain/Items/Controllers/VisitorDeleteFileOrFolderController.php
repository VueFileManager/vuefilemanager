<?php
namespace Domain\Items\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\Items\Actions\DeleteFileOrFolderAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Delete item for guest user with edit permission
 */
class VisitorDeleteFileOrFolderController extends Controller
{
    public function __construct(
        private DeleteFileOrFolderAction $deleteFileOrFolder,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
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
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        foreach ($request->input('items') as $file) {
            // Get file|folder item
            $item = get_item($file['type'], $file['id']);

            // Check access to requested item
            if ($file['type'] === 'folder') {
                ($this->verifyAccessToItem)($item->id, $shared);
            } else {
                ($this->verifyAccessToItem)($item->folder_id, $shared);
            }

            // Delete item
            ($this->deleteFileOrFolder)($file, $file['id'], $shared);
        }

        return response('Done', 204);
    }
}
