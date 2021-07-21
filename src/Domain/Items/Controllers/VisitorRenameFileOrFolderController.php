<?php
namespace Domain\Items\Controllers;

use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Domain\Items\Requests\RenameItemRequest;
use Domain\Items\Actions\RenameFileOrFolderAction;
use Domain\Folders\Actions\UpdateFolderPropertyAction;
use Support\Demo\Actions\FakeRenameFileOrFolderAction;

/**
 * Rename item for guest user with edit permission
 */
class VisitorRenameFileOrFolderController extends Controller
{
    public function __construct(
        private HelperService $helper,
        private RenameFileOrFolderAction $renameFileOrFolder,
        private UpdateFolderPropertyAction $updateFolderProperty,
        private FakeRenameFileOrFolderAction $fakeRenameFileOrFolder,
    ) {
    }

    public function __invoke(
        RenameItemRequest $request,
        string $id,
        Share $shared,
    ): Response {
        // Return fake renamed item in demo
        if (is_demo_account($shared->user->email)) {
            return ($this->fakeRenameFileOrFolder)($request, $id);
        }

        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check shared permission
        if (is_visitor($shared)) {
            abort(403);
        }

        // Get file|folder item
        $item = get_item($request->input('type'), $id);

        // Check access to requested item
        if ($request->input('type') === 'folder') {
            $this->helper->check_item_access($item->id, $shared);
        } else {
            $this->helper->check_item_access($item->folder_id, $shared);
        }

        // If request have a change folder icon values set the folder icon
        if ($request->input('type') === 'folder' && $request->filled('icon')) {
            ($this->updateFolderProperty)($request, $id);
        }

        // Rename item
        $item = ($this->renameFileOrFolder)($request, $id);

        // Set public url
        if ($item->type !== 'folder') {
            $item->setPublicUrl($shared->token);
        }

        return response($item, 201);
    }
}
