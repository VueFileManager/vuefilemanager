<?php
namespace Domain\Items\Controllers;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Items\Actions\MoveFileOrFolderAction;

/**
 * Move item for guest user with edit permission
 */
class VisitorMoveFileOrFolderController extends Controller
{
    public function __construct(
        private HelperService $helper,
        private MoveFileOrFolderAction $moveFileOrFolder,
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
        $this->helper->check_protected_share_record($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        foreach ($request->input('items') as $item) {
            if ($item['type'] === 'folder') {
                $this->helper->check_item_access([
                    $request->input('to_id'), $item['id'],
                ], $shared);
            }

            if ($item['type'] !== 'folder') {
                $file = File::where('id', $item['id'])
                    ->where('user_id', $shared->user_id)
                    ->firstOrFail();

                $this->helper->check_item_access([
                    $request->input('to_id'), $file->folder_id,
                ], $shared);
            }
        }

        ($this->moveFileOrFolder)($request, $request->to_id);

        return response('Done!', 204);
    }
}
