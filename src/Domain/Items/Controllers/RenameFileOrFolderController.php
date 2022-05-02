<?php
namespace Domain\Items\Controllers;

use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Files\Resources\FileResource;
use Domain\Folders\Resources\FolderResource;
use Domain\Items\Requests\RenameItemRequest;
use Domain\Items\Actions\RenameFileOrFolderAction;
use Illuminate\Auth\Access\AuthorizationException;
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
     *
     * @throws AuthorizationException
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
    ): FileResource | FolderResource | File | Folder | array {
        if (is_demo_account()) {
            $item = ($this->fakeRenameFileOrFolder)($request, $id);

            if ($request->input('type') === 'folder') {
                return new FolderResource($item);
            }

            return new FileResource($item);
        }

        // If request contain icon or color, then change it
        if ($request->input('type') === 'folder' && $request->hasAny(['emoji', 'color'])) {
            ($this->updateFolderProperty)($request, $id);
        }

        // Rename item
        $item = ($this->renameFileOrFolder)($request, $id);

        if ($request->input('type') === 'folder') {
            return new FolderResource($item);
        }

        return new FileResource($item);
    }
}
