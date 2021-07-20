<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Support\Demo\Actions\DemoService;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\Folders\Requests\CreateFolderRequest;

class CreateFolderController extends Controller
{
    public function __construct(
        public DemoService $demo
    ) {
    }

    /**
     * Create new folder for authenticated master|editor user
     */
    public function __invoke(
        CreateFolderRequest $request,
        CreateFolderAction $createFolder,
    ): Response {
        // If is demo, return fake folder
        if (is_demo_account('howdy@hi5ve.digital')) {
            return $this->demo->create_folder($request);
        }

        $folder = ($createFolder)($request);

        // Create new folder
        return response($folder, 201);
    }
}
