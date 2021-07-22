<?php
namespace Domain\Items\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Domain\Items\Requests\RenameItemRequest;
use Domain\Items\Actions\RenameFileOrFolderAction;
use Domain\Folders\Actions\UpdateFolderPropertyAction;
use Support\Demo\Actions\FakeRenameFileOrFolderAction;

class RenameFileOrFolderController extends Controller
{
    public function __construct(
        public RenameFileOrFolderAction $renameFileOrFolder,
        public UpdateFolderPropertyAction $updateFolderProperty,
        public FakeRenameFileOrFolderAction $fakeRenameFileOrFolder,
    ) {
    }

    /**
     * Rename item for authenticated master|editor user
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
    ): Model | array {
        if (is_demo_account(Auth::user()->email)) {
            return ($this->fakeRenameFileOrFolder)($request, $id);
        }

        // If request contain icon or color, then change it
        if ($request->filled('emoji') || $request->filled('color')) {
            ($this->updateFolderProperty)($request, $id);
        }

        // Rename Item
        return ($this->renameFileOrFolder)($request, $id);
    }
}
