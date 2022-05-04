<?php
namespace Domain\Items\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\DeleteItemRequest;
use Domain\Items\Actions\DeleteFileOrFolderAction;

class DeleteFileOrFolderController extends Controller
{
    public function __construct(
        public DeleteFileOrFolderAction $deleteFileOrFolder,
    ) {
    }

    /**
     * Delete item for authenticated master|editor user
     */
    public function __invoke(
        DeleteItemRequest $request,
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'Items was successfully deleted.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        foreach ($request->input('items') as $item) {
            ($this->deleteFileOrFolder)($item, $item['id']);
        }

        return response()->json($successMessage);
    }
}
