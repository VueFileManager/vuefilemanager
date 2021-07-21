<?php
namespace Domain\Folders\Actions;

use Domain\Folders\Models\Folder;

class UpdateFolderPropertyAction
{
    /**
     * Update folder icon or style
     */
    public function __invoke($request, string $id): void
    {
        // Get folder
        $folder = Folder::find($id);

        // Set default folder icon
        if ($request->emoji === 'default') {
            $folder->update([
                'emoji' => null,
                'color' => null,
            ]);
        }

        // Set emoji
        if ($request->filled('emoji')) {
            $folder->update([
                'emoji' => $request->emoji,
                'color' => null,
            ]);
        }

        // Set color
        if ($request->filled('color')) {
            $folder->update([
                'emoji' => null,
                'color' => $request->color,
            ]);
        }
    }
}
