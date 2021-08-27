<?php
namespace Domain\Items\Actions;

use Domain\Files\Resources\FileResource;
use Domain\Folders\Resources\FolderResource;
use Domain\Items\Requests\RenameItemRequest;

class RenameFileOrFolderAction
{
    /**
     * Rename item name
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
    ): FolderResource|FileResource|array {

        // Get item
        $item = get_item($request->input('type'), $id);

        // Rename item
        $item->update([
            'name' => $request->input('name'),
        ]);

        if ($request->input('type') === 'folder') {
            return new FolderResource($item);
        }

        // Return updated item
        return new FileResource($item);
    }
}
