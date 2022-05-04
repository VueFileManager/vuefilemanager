<?php
namespace Domain\Items\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\MoveItemRequest;
use Domain\Items\Actions\MoveFileOrFolderAction;

class MoveFileOrFolderController extends Controller
{
    public function __construct(
        public MoveFileOrFolderAction $moveFileOrFolder,
    ) {
    }

    /**
     * Move item for authenticated master|editor user
     */
    public function __invoke(
        MoveItemRequest $request,
    ): JsonResponse {
        $successMessage = [
            'type'    => 'success',
            'message' => 'Items was successfully moved.',
        ];

        if (isDemoAccount()) {
            return response()->json($successMessage);
        }

        // Move items
        ($this->moveFileOrFolder)($request);

        return response()->json($successMessage);
    }
}
