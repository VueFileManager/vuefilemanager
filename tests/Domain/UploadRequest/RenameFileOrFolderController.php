<?php
namespace Tests\Domain\UploadRequest;

use App\Http\Controllers\Controller;
use Domain\Files\Resources\FileResource;
use Domain\Folders\Resources\FolderResource;
use Domain\Items\Requests\RenameItemRequest;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Folders\Actions\UpdateFolderPropertyAction;
use Support\Demo\Actions\FakeRenameFileOrFolderAction;

class RenameFileOrFolderController extends Controller
{
    public function __construct(
        public UpdateFolderPropertyAction $updateFolderProperty,
        public FakeRenameFileOrFolderAction $fakeRenameFileOrFolder,
    ) {
    }

    public function __invoke(UploadRequest $uploadRequest, string $id, RenameItemRequest $request)
    {
        // Check if upload request is active
        if ($uploadRequest->status !== 'active') {
            return response('Gone', 410);
        }

        // Get item
        $item = get_item($request->input('type'), $id);

        // Check privileges
        if (! in_array($item->parent_id, getChildrenFolderIds($uploadRequest->id))) {
            return response('Access Denied', 403);
        }

        // If request contain icon or color, then change it
        if ($request->input('type') === 'folder' && $request->hasAny(['emoji', 'color'])) {
            ($this->updateFolderProperty)($request, $id);
        }

        // Update item
        $item->update(['name' => $request->input('name')]);

        if ($request->input('type') === 'folder') {
            return new FolderResource($item);
        }

        return new FileResource($item);
    }
}
