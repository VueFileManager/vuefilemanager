<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\Folders\Requests\CreateFolderRequest;
use Support\Demo\Actions\FakeCreateFolderAction;
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
        private FakeCreateFolderAction $fakeCreateFolderAction,
    ) {
    }

    public function __invoke(
        CreateFolderRequest $request,
        Share $shared,
    ): Response | array {
        if (is_demo_account()) {
            return ($this->fakeCreateFolderAction)($request);
        }

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Check access to requested directory
        ($this->verifyAccessToItem)($request->parent_id, $shared);

        // Create folder
        $folder = ($this->createFolder)($request, $shared);

        return response($folder, 201);
    }
}
