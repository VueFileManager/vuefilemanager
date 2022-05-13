<?php
namespace Domain\Items\Controllers;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Items\Actions\MoveFileOrFolderAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Move item for guest user with edit permission
 */
class VisitorMoveFileOrFolderController extends Controller
{
    public function __construct(
        private MoveFileOrFolderAction $moveFileOrFolder,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        MoveItemRequest $request,
        Share $shared,
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'Items was successfully moved.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        // Check shared permission
        if (is_visitor($shared)) {
            return response()->json(accessDeniedError(), 403);
        }

        // Add default parent id if missing
        if ($request->missing('to_id')) {
            $request->merge(['to_id' => $shared->item_id]);
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
                    $request->input('to_id'), $file->parent_id,
                ], $shared);
            }
        }

        ($this->moveFileOrFolder)($request, $shared);

        return response()->json($successMessage);
    }
}
