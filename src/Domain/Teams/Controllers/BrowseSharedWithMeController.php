<?php
namespace Domain\Teams\Controllers;

use Str;
use Gate;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Domain\Folders\Resources\FolderResource;

class BrowseSharedWithMeController
{
    public function __invoke(Request $request, $id): array
    {
        $id = Str::isUuid($id) ? $id : null;

        if ($id) {
            $teamFolder = Folder::findOrFail($id)->getLatestParent();

            if (! Gate::any(['can-edit', 'can-view'], [$teamFolder, null])) {
                abort(403, 'Access Denied');
            }

            $folders = Folder::with(['parent:id,name'])
                ->where('parent_id', $id)
                ->sortable()
                ->get();

            $files = File::with(['parent:id,name'])
                ->where('parent_id', $id)
                ->sortable()
                ->get();
        }

        if (! $id) {
            $sharedFolderIds = DB::table('team_folder_members')
                ->where('user_id', Auth::id())
                ->whereIn('permission', ['can-edit', 'can-view'])
                ->pluck('parent_id');

            $folders = Folder::whereIn('id', $sharedFolderIds)
                ->sortable()
                ->get();
        }

        list($data, $paginate, $links) = groupPaginate($request, $folders, $files ?? null);

        return [
            'data'       => $data,
            'teamFolder' => $id ? new FolderResource($teamFolder) : null,
            'links'      => $links,
            'meta'       => [
                'paginate' => $paginate,
                'root'     => $id ? new FolderResource(Folder::findOrFail($id)) : null
            ]
        ];
    }
}
