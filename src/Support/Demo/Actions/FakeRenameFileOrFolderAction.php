<?php
namespace Support\Demo\Actions;

use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Domain\Items\Requests\RenameItemRequest;

class FakeRenameFileOrFolderAction
{
    /**
     * Rename item name
     */
    public function __invoke(
        RenameItemRequest $request,
        string $id,
    ): array|File {
        // Get item
        if ($request->input('type') === 'folder') {
            $item = Folder::where('id', $id)
                ->first();
        } else {
            $item = File::where('id', $id)
                ->first();
        }

        if ($item) {
            $item->name = $request->input('name');
            $item->emoji = $request->input('icon.emoji') ?? null;
            $item->color = $request->input('icon.color') ?? null;

            return $item;
        }

        return [
            'id'   => $request->input('id'),
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ];
    }
}
