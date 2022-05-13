<?php
namespace Domain\Folders\Controllers;

use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\Folders\Requests\CreateFolderRequest;
use Support\Demo\Actions\FakeCreateFolderAction;
use App\Users\Exceptions\InvalidUserActionException;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Create new folder for guest user with edit permission
 */
class VisitorCreateFolderController extends Controller
{
    public function __construct(
        private CreateFolderAction $createFolder,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
        private FakeCreateFolderAction $fakeCreateFolder,
    ) {
    }

    public function __invoke(
        CreateFolderRequest $request,
        Share $shared,
    ): JsonResponse {
        if (isDemoAccount()) {
            $fakeFolder = ($this->fakeCreateFolder)($request);

            return response()->json(new FolderResource($fakeFolder), 201);
        }

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            return response()->json(accessDeniedError(), 403);
        }

        // Add default parent id if missing
        if ($request->missing('parent_id')) {
            $request->merge(['parent_id' => $shared->item_id]);
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->input('parent_id'), $shared);

        try {
            // Create new folder
            $folder = ($this->createFolder)($request, $shared);

            // Return new folder
            return response()->json(new FolderResource($folder), 201);
        } catch (InvalidUserActionException $e) {
            // Return error response
            return response()->json([
                'type'    => 'error',
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
