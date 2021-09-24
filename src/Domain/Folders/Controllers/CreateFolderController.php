<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\Folders\Requests\CreateFolderRequest;
use Support\Demo\Actions\FakeCreateFolderAction;

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
    ): Response {
        if (is_demo_account()) {
            $fakeFolder = ($this->fakeCreateFolder)($request);

            return response(new FolderResource($fakeFolder), 201);
        }

        $folder = ($this->createFolder)($request);

        return response(new FolderResource($folder), 201);
    }
}
