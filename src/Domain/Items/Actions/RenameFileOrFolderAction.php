<?php
namespace Domain\Items\Actions;

use Gate;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Domain\Items\Requests\RenameItemRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RenameFileOrFolderAction
{
    /**
     * Rename item name
     *
     * @throws AuthorizationException
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
        ?Share $shared = null,
    ): File | Folder {
        $item = get_item($request->input('type'), $id);

        Gate::authorize('can-edit', [$item, $shared]);

        $item->update([
            'name' => $request->input('name'),
        ]);

        return $item;
    }
}
