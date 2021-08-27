<?php
namespace Domain\Folders\Actions;

use Domain\Folders\Models\Folder;
use Domain\Items\Requests\RenameItemRequest;

class UpdateFolderPropertyAction
{
    /**
     * Update folder icon or style
     */
    public function __invoke(RenameItemRequest $request, string $id): void
    {
        // Get folder
        $folder = Folder::find($id);

        // Set default folder icon
        if ($request->input('emoji') === 'default') {
            $folder->update([
                'emoji' => null,
                'color' => null,
            ]);
        }

        // Set emoji
        if ($request->filled('emoji')) {
            $folder->update([
                'emoji' => $request->input('emoji'),
                'color' => null,
            ]);
        }

        // Set color
        if ($request->filled('color')) {
            $folder->update([
                'emoji' => null,
                'color' => $request->input('color'),
            ]);
        }
    }
}
