<?php
namespace Domain\Folders\Controllers;

use Auth;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
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
    ): Response | array {
        // If is demo, return fake folder
        if (is_demo_account(Auth::user()->email)) {
            return ($this->fakeCreateFolder)($request);
        }

        // CreateFolder
        $folder = ($this->createFolder)($request);

        return response($folder, 201);
    }
}
