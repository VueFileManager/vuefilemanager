<?php
namespace Domain\Items\Controllers;

use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\Items\Actions\DeleteFileOrFolderAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Delete item for guest user with edit permission
 */
class VisitorDeleteFileOrFolderController extends Controller
{
    public function __construct(
        private DeleteFileOrFolderAction $deleteFileOrFolder,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        DeleteItemRequest $request,
        Share $shared,
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'Items was successfully deleted.',
        ];

        if (isDemoAccount()) {
            return response()->json($message, 204);
        }

        // Check shared permission
        if (is_visitor($shared)) {
            return response()->json(accessDeniedError(), 403);
        }

        foreach ($request->input('items') as $file) {
            // Get file|folder item
            $item = get_item($file['type'], $file['id']);

            // Check access to requested item
            if ($file['type'] === 'folder') {
                ($this->verifyAccessToItem)($item->id, $shared);
            } else {
                ($this->verifyAccessToItem)($item->parent_id, $shared);
            }

            // Delete item
            ($this->deleteFileOrFolder)($file, $file['id'], $shared);
        }

        return response()->json($message, 204);
    }
}
