<?php
namespace Domain\Browsing\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class BrowseFolderContentController
{
    public function __invoke(
        Request $request,
        string $id,
    ): Collection {
        $root_id = $id === 'undefined' ? null : $id;

        // Get folder trash items
        if ($request->query('trash')) {
            // Get folders and files
            $folders = Folder::onlyTrashed()
                ->with('parent')
                ->where('parent_id', $root_id)
                ->sortable()
                ->get();

            $files = File::onlyTrashed()
                ->with('parent')
                ->where('folder_id', $root_id)
                ->sortable()
                ->get();

            // Collect folders and files to single array
            return collect([$folders, $files])->collapse();
        }

        // Get folders and files
        $folders = Folder::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('parent_id', $root_id)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get();

        $files = File::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('folder_id', $root_id)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get();

        // Collect folders and files to single array
        return collect([$folders, $files])
            ->collapse();
    }
}
