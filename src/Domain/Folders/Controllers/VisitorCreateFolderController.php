<?php
namespace Domain\Folders\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Support\Demo\Actions\FakeCreateFolderAction;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Domain\Folders\Actions\CreateFolderAction;
use Domain\Folders\Requests\CreateFolderRequest;

/**
 * Create new folder for guest user with edit permission
 */
class VisitorCreateFolderController extends Controller
{
    public function __construct(
        public HelperService $helper,
    ) {}

    public function __invoke(
        FakeCreateFolderAction $fakeCreateFolderAction,
        CreateFolderAction $createFolder,
        CreateFolderRequest $request,
        Share $shared,
    ): Response | array {
        if (is_demo_account($shared->user->email)) {
            return ($fakeCreateFolderAction)($request);
        }

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Check access to requested directory
        $this->helper->check_item_access($request->parent_id, $shared);

        // Create folder
        $folder = ($createFolder)($request, $shared);

        return response($folder, 201);
    }
}
