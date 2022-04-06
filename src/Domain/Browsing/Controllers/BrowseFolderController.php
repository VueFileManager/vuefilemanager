<?php
namespace Domain\Browsing\Controllers;

use Str;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Domain\Folders\Resources\FolderResource;

class BrowseFolderController
{
    public function __invoke(
        Request $request,
        string $id,
    ): array {
        $root_id = Str::isUuid($id) ? $id : null;

        $folders = Folder::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('parent_id', $root_id)
            ->where('team_folder', false)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get();

        $files = File::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where('parent_id', $root_id)
            ->where('user_id', Auth::id())
            ->sortable()
            ->get();

        list($data, $paginate, $links) = groupPaginate($request, $folders, $files);

        return [
            'data'  => $data,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => $root_id ? new FolderResource(Folder::findOrFail($root_id)) : null,
            ]
        ];
    }
}
