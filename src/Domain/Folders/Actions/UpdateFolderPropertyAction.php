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

        $folder->update([
            'emoji' => $request->input('emoji'),
            'color' => $request->input('color'),
        ]);
    }
}
