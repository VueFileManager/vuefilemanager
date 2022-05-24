<?php
namespace Domain\Browsing\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;

class BrowseSharedController
{
    public function __invoke(Request $request): JsonResponse
    {
        $userId = auth()->id();

        $page = request()->has('page')
            ? request()->input('page')
            : 'all';

        // Get shared folders and files
        $parentIds = Share::where('user_id', $userId)
            ->where('type', 'folder')
            ->pluck('item_id')
            ->toArray();

        $fileIds = Share::where('user_id', $userId)
            ->where('type', '!=', 'folder')
            ->pluck('item_id')
            ->toArray();

        $query = [
            'folder' => [
                'where'   => [
                    'user_id' => $userId,
                ],
                'whereIn' => [
                    'id' => $parentIds,
                ],
            ],
            'file'   => [
                'where'   => [
                    'user_id' => $userId,
                ],
                'whereIn' => [
                    'id' => $fileIds,
                ],
            ],
            'with'   => [
                'parent',
                'shared:token,id,item_id,permission,is_protected,expire_in',
            ],
        ];

        [$foldersTake, $foldersSkip, $filesTake, $filesSkip, $totalEntries] = getRecordsCount($query, $page);

        $folders = Folder::with($query['with'])
            ->where($query['folder']['where'])
            ->whereIn('id', $parentIds, )
            ->sortable()
            ->skip($foldersSkip)
            ->take($foldersTake)
            ->get();

        $files = File::with($query['with'])
            ->where($query['file']['where'])
            ->whereIn('id', $fileIds)
            ->sortable()
            ->skip($filesSkip)
            ->take($filesTake)
            ->get();

        // Collect entries
        $entries = collect([
            $folders ? json_decode((new FolderCollection($folders))->toJson(), true) : null,
            $files ? json_decode((new FilesCollection($files))->toJson(), true) : null,
        ])->collapse();

        // Get paginator metadata
        [$paginate, $links] = formatPaginatorMetadata($totalEntries);

        // Collect folders and files to single array
        return response()->json([
            'data'  => $entries,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => null,
            ],
        ]);
    }
}
