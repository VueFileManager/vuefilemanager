<?php
namespace Domain\Browsing\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;

class BrowseTrashContentController
{
    public function __invoke(Request $request, string $id): array
    {
        $user_id = Auth::id();
        $root_id = $id === 'undefined' ? null : $id;
        $requestedFolder = $root_id ? Folder::withTrashed()->findOrFail($root_id) : null;

        if ($root_id) {
            // Get folders and files
            $folders = Folder::onlyTrashed()
                ->with('parent')
                ->where('parent_id', $root_id)
                ->sortable()
                ->get();

            $files = File::onlyTrashed()
                ->with('parent')
                ->where('parent_id', $root_id)
                ->sortable()
                ->get();
        } else {
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
            $files = File::onlyTrashed()
                ->with(['parent'])
                ->where('user_id', $user_id)
                ->where(function ($query) use ($folders_trashed) {
                    $query->whereNull('parent_id');
                    $query->orWhereNotIn('parent_id', array_values(array_unique(recursiveFind($folders_trashed->toArray(), 'id'))));
                })
                ->sortable()
                ->get();
        }

        list($data, $paginate, $links) = groupPaginate($request, $folders, $files);

        // Collect folders and files to single array
        return [
            'data'  => $data,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => $requestedFolder,
            ],
        ];
    }
}
