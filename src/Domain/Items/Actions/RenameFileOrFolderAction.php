<?php
namespace Domain\Items\Actions;

use Domain\Items\Requests\RenameItemRequest;

class RenameFileOrFolderAction
{
    /**
     * Rename item name
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
    ) {
        // Get item
        $item = get_item($request->input('type'), $id);

        // Rename item
        $item->update([
            'name' => $request->input('name'),
        ]);

        return $item;
    }
}
