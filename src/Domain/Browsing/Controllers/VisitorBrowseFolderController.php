<?php
namespace Domain\Browsing\Controllers;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Resources\FolderCollection;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;
use Str;

/**
 * Browse shared folder
 */
class VisitorBrowseFolderController
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
    ) {
    }

    public function __invoke(
        string $id,
        Share $shared,
    ): JsonResponse {

        $folderId = Str::isUuid($id)
            ? $id
            : $shared->item_id;

        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check if user can get directory
        ($this->verifyAccessToItem)($folderId, $shared);

        // Get requested folder
        $requestedFolder = Folder::findOrFail($folderId);

        $page = request()->has('page')
            ? request()->input('page')
            : 'all';

        // Prepare folder & file db query
        $query = [
            'folder' => [
                'where' => [
                    'parent_id'   => $folderId,
                    'user_id'     => $shared->user_id,
                ],
            ],
            'file' => [
                'where' => [
                    'parent_id'   => $folderId,
                    'user_id'     => $shared->user_id,
                ],
            ],
        ];

        [$foldersTake, $foldersSkip, $filesTake, $filesSkip, $totalEntries] = getRecordsCount($query, $page);

        $folders = Folder::where($query['folder']['where'])
            ->sortable()
            ->skip($foldersSkip)
            ->take($foldersTake)
            ->get();

        $files = File::where($query['file']['where'])
            ->sortable()
            ->skip($filesSkip)
            ->take($filesTake)
            ->get();

        // Set thumbnail links for public files
        $files->map(fn ($file) => $file->setSharedPublicUrl($shared->token));

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
                'root'     => new FolderResource($requestedFolder),
            ],
        ]);
    }
}
