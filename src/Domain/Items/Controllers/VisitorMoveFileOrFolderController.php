<?php
namespace Domain\Items\Controllers;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Items\Actions\MoveFileOrFolderAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Move item for guest user with edit permission
 */
class VisitorMoveFileOrFolderController extends Controller
{
    public function __construct(
        private MoveFileOrFolderAction $moveFileOrFolder,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        MoveItemRequest $request,
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

        foreach ($request->input('items') as $item) {
            if ($item['type'] === 'folder') {
                ($this->verifyAccessToItem)([
                    $request->input('to_id'), $item['id'],
                ], $shared);
            }

            if ($item['type'] !== 'folder') {
                $file = File::where('id', $item['id'])
                    ->where('user_id', $shared->user_id)
                    ->firstOrFail();

                ($this->verifyAccessToItem)([
                    $request->input('to_id'), $file->folder_id,
                ], $shared);
            }
        }

        ($this->moveFileOrFolder)($request, $request->to_id);

        return response('Done!', 204);
    }
}
