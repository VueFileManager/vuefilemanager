<?php
namespace Domain\UploadRequest\Controllers;

use App\Http\Controllers\Controller;
use Domain\Items\Requests\MoveItemRequest;
use Domain\UploadRequest\Models\UploadRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class MoveItemInUploadRequestController extends Controller
{
    public function __invoke(
        MoveItemRequest $request,
        UploadRequest $uploadRequest,
    ): JsonResponse {
        foreach ($request->input('items') as $item) {
            $item = get_item($item['type'], $item['id']);

            // Check privileges
            if (! in_array($item['parent_id'], getChildrenFolderIds($uploadRequest->id))) {
                return response()->json(accessDeniedError(), 403);
            }

            $item->update([
                'parent_id' => $request->input('to_id') ?? $uploadRequest->id,
            ]);
        }

        return response()->json([
            'type'    => 'success',
            'message' => 'Items was successfully moved.',
        ]);
    }
}
