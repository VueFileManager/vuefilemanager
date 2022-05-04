<?php
namespace Domain\Trash\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Trash\Requests\RestoreTrashContentRequest;

class RestoreTrashContentController extends Controller
{
    public function __invoke(
        RestoreTrashContentRequest $request
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'The item was successfully restored.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        foreach ($request->input('items') as $item) {
            $entry = get_item($item['type'], $item['id']);

            // Restore item to home directory
            if ($request->has('to_home') && $request->input('to_home')) {
                $entry->update(['parent_id' => null]);
            }

            // Restore Item
            $entry->restore();
        }

        // Return response
        return response()->json($successMessage);
    }
}
