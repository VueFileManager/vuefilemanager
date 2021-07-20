<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BrowseTrashContentController
{
    public function __invoke(): Collection
    {
        $user_id = Auth::id();

        // Get folders and files
        $folders_trashed = Folder::onlyTrashed()
            ->with(['trashedFolders', 'parent'])
            ->where('user_id', $user_id)
            ->get(['parent_id', 'id', 'name']);

        $folders = Folder::onlyTrashed()
            ->with(['parent'])
            ->where('user_id', $user_id)
            ->whereIn('id', filter_folders_ids($folders_trashed))
            ->sortable()
            ->get();

        // Get files trashed
        $files_trashed = File::onlyTrashed()
            ->with(['parent'])
            ->where('user_id', $user_id)
            ->where(function ($query) use ($folders_trashed) {
                $query->whereNull('folder_id');
                $query->orWhereNotIn('folder_id', array_values(array_unique(recursiveFind($folders_trashed->toArray(), 'id'))));
            })
            ->sortable()
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files_trashed])
            ->collapse();
    }
}
