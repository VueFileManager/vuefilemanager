<?php
namespace Domain\Browsing\Controllers;

use Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Domain\Folders\Resources\FolderResource;

class BrowseFolderController
{
    public function __invoke(
        string $id,
    ): array {
        $root_id = Str::isUuid($id) ? $id : null;

        $folderQuery = [
            'parent_id'   => $root_id,
            'team_folder' => false,
            'user_id'     => Auth::id(),
            'deleted_at'  => null
        ];

        $fileQuery = [
            'parent_id'   => $root_id,
            'user_id'     => Auth::id(),
            'deleted_at'  => null
        ];

        list($foldersTake, $foldersSkip, $filesTake, $filesSkip, $totalItemsCount) = getRecordsCount($folderQuery, $fileQuery);

        $folders = Folder::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where($folderQuery)
            ->sortable()
            ->skip($foldersSkip)
            ->take($foldersTake)
            ->get();
            
        $files = File::with(['parent:id,name', 'shared:token,id,item_id,permission,is_protected,expire_in'])
            ->where($fileQuery)
            ->sortable()
            ->skip($filesSkip)
            ->take($filesTake)
            ->get();
            
        list($data, $paginate, $links) = groupPaginate($folders, $files, $totalItemsCount);

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
