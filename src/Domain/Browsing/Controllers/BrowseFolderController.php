<?php
namespace Domain\Browsing\Controllers;

use Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Resources\FolderCollection;

class BrowseFolderController
{
    public function __invoke(
        string $id,
    ): JsonResponse {
        $rootId = Str::isUuid($id)
            ? $id
            : null;

        $page = request()->has('page')
            ? request()->input('page')
            : 'all';

        // Prepare folder & file db query
        $query = [
            'folder' => [
                'where' => [
                    'parent_id'   => $rootId,
                    'team_folder' => false,
                    'user_id'     => auth()->id(),
                    'deleted_at'  => null,
                ],
            ],
            'file' => [
                'where' => [
                    'parent_id'   => $rootId,
                    'user_id'     => auth()->id(),
                    'deleted_at'  => null,
                ],
            ],
            'with' => [
                'parent:id,name',
                'shared:token,id,item_id,permission,is_protected,expire_in',
            ],
        ];

        [$foldersTake, $foldersSkip, $filesTake, $filesSkip, $totalEntries] = getRecordsCount($query, $page);

        $folders = Folder::with($query['with'])
            ->where($query['folder']['where'])
            ->sortable()
            ->skip($foldersSkip)
            ->take($foldersTake)
            ->get();
            
        $files = File::with($query['with'])
            ->where($query['file']['where'])
            ->sortable()
            ->skip($filesSkip)
            ->take($filesTake)
            ->get();
        
        $entries = collect([
            $folders ? json_decode((new FolderCollection($folders))->toJson(), true) : null,
            $files ? json_decode((new FilesCollection($files))->toJson(), true) : null,
        ])->collapse();
            
        [$paginate, $links] = formatPaginatorMetadata($totalEntries);

        return response()->json([
            'data'  => $entries,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => $rootId ? new FolderResource(Folder::findOrFail($rootId)) : null,
            ],
        ]);
    }
}
