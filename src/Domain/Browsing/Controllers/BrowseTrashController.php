<?php
namespace Domain\Browsing\Controllers;

use Str;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderCollection;

class BrowseTrashController
{
    public function __invoke(
        string $id
    ): JsonResponse {
        $userId = auth()->id();

        $rootId = Str::isUuid($id)
            ? $id
            : null;

        $requestedFolder = $rootId
            ? Folder::withTrashed()
                ->findOrFail($rootId)
            : null;

        $page = request()->has('page')
            ? request()->input('page')
            : 'all';

        // Load trashed folder content
        if ($rootId) {
            // Prepare folder & file db query
            $query = [
                'folder' => [
                    'where' => [
                        'parent_id'   => $rootId,
                    ],
                ],
                'file' => [
                    'where' => [
                        'parent_id'   => $rootId,
                    ],
                ],
            ];

            [$foldersTake, $foldersSkip, $filesTake, $filesSkip, $totalEntries] = getRecordsCount($query, $page);

            // Get folders and files
            $folders = Folder::onlyTrashed()
                ->with('parent')
                ->where($query['folder']['where'])
                ->sortable()
                ->skip($foldersSkip)
                ->take($foldersTake)
                ->get();

            $files = File::onlyTrashed()
                ->with('parent')
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
        }

        // Load trash root
        if (! $rootId) {
            // Get folders and files
            $folders_trashed = Folder::onlyTrashed()
                ->with(['trashedFolders', 'parent'])
                ->where('user_id', $userId)
                ->get(['parent_id', 'id', 'name']);

            // Prepare folder & file db query
            $query = [
                'folder' => [
                    'where' => [
                        'user_id'   => $userId,
                    ],
                    'whereIn' => [
                        'id' => filter_folders_ids($folders_trashed),
                    ],
                ],
                'file' => [
                    'where' => function ($query) use ($folders_trashed, $userId) {
                        $query
                            ->where('user_id', $userId)
                            ->whereNull('parent_id')
                            ->orWhereNotIn('parent_id', array_values(array_unique(recursiveFind($folders_trashed->toArray(), 'id'))));
                    },
                ],
            ];

            [$foldersTake, $foldersSkip, $filesTake, $filesSkip, $totalEntries] = getRecordsCount($query, $page, true);

            $folders = Folder::onlyTrashed()
                ->with(['parent'])
                ->where($query['folder']['where'])
                ->whereIn('id', filter_folders_ids($folders_trashed))
                ->sortable()
                ->skip($foldersSkip)
                ->take($foldersTake)
                ->get();

            $files = File::onlyTrashed()
                ->with(['parent'])
                ->where('user_id', $userId)
                ->where(function ($query) use ($folders_trashed) {
                    $query->whereNull('parent_id');
                    $query->orWhereNotIn('parent_id', array_values(array_unique(recursiveFind($folders_trashed->toArray(), 'id'))));
                })
                ->sortable()
                ->skip($filesSkip)
                ->take($filesTake)
                ->get();

            // Collect folders and files to single array
            $entries = collect([
                $folders ? json_decode((new FolderCollection($folders))->toJson(), true) : null,
                $files ? json_decode((new FilesCollection($files))->toJson(), true) : null,
            ])->collapse();

            [$paginate, $links] = formatPaginatorMetadata($totalEntries);
        }

        // Collect folders and files to single array
        return response()->json([
            'data'  => $entries,
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
                'root'     => $requestedFolder,
            ],
        ]);
    }
}
