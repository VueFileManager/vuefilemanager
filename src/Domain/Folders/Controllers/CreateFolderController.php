<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\Folders\Requests\CreateFolderRequest;
use Support\Demo\Actions\FakeCreateFolderAction;
use App\Users\Exceptions\InvalidUserActionException;

class CreateFolderController extends Controller
{
    public function __construct(
        public CreateFolderAction $createFolder,
        public FakeCreateFolderAction $fakeCreateFolder,
    ) {
    }

    /**
     * Create new folder for authenticated master|editor user
     */
    public function __invoke(
        CreateFolderRequest $request,
    ): JsonResponse {
        if (isDemoAccount()) {
            $fakeFolder = ($this->fakeCreateFolder)($request);

            return response()->json(new FolderResource($fakeFolder), 201);
        }

        try {
            // Create new folder
            $folder = ($this->createFolder)($request);

            // Return new folder
            return response()->json(new FolderResource($folder), 201);
        } catch (InvalidUserActionException $e) {
            return response()->json([
                'type'    => 'error',
                'message' => $e->getMessage(),
            ], 401);
        }
    }
}
