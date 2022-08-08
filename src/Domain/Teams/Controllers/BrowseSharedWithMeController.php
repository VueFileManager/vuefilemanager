<?php
namespace Domain\Teams\Controllers;

use Str;
use Gate;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Domain\Folders\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Domain\Files\Resources\FilesCollection;
use Domain\Folders\Resources\FolderResource;
use Domain\Folders\Resources\FolderCollection;

class BrowseSharedWithMeController
{
    public function __invoke(
        Request $request,
        string $id,
    ): JsonResponse {
        // Get root ID
        $id = Str::isUuid($id)
            ? $id
            : null;

        // Get page number
        $page = request()->has('page')
            ? request()->input('page')
            : 'all';

        if ($id) {
            [$teamFolder, $folders, $files, $totalEntries] = $this->getSingleSharedWithMeFolderContent($id, $page);
        } else {
            [$folders, $files, $totalEntries] = $this->getRootFolders($page);
        }

        // Get paginator data
        [$paginate, $links] = formatPaginatorMetadata($totalEntries);

        // Collect entries
        $entries = collect([
            $folders ? json_decode((new FolderCollection($folders))->toJson(), true) : null,
            $files ? json_decode((new FilesCollection($files))->toJson(), true) : null,
        ])->collapse();

        return response()->json([
            'data'  => $entries,
            'links' => $links,
            'meta'  => [
                'paginate'   => $paginate,
                'teamFolder' => $id
                    ? new FolderResource($teamFolder)
                    : null,
                'root'       => $id
                    ? new FolderResource(Folder::findOrFail($id))
                    : null,
            ],
        ]);
    }

    private function getRootFolders(mixed $page): array
    {
        $entriesPerPage = config('vuefilemanager.paginate.perPage');

        $sharedFolderIds = DB::table('team_folder_members')
            ->where('user_id', auth()->id())
            ->whereIn('permission', ['can-edit', 'can-view'])
            ->pluck('parent_id');

        $folders = Folder::whereIn('id', $sharedFolderIds)
            ->sortable()
            ->skip($entriesPerPage * ($page - 1))
            ->take($entriesPerPage)
            ->get();

        $totalEntries = DB::table('folders')
            ->whereIn('id', $sharedFolderIds)
            ->count();

        $files = null;

        return [$folders, $files, $totalEntries];
    }

    private function getSingleSharedWithMeFolderContent(string|null $id, mixed $page): array
    {
        $teamFolder = Folder::findOrFail($id)
            ->getLatestParent();

        if (! Gate::any(['can-edit', 'can-view'], [$teamFolder, null])) {
            abort(
                response()->json(accessDeniedError(), 403)
            );
        }

        $query = [
            'folder' => [
                'where' => [
                    'parent_id' => $id,
                ],
            ],
            'file'   => [
                'where' => [
                    'parent_id' => $id,
                ],
            ],
            'with'   => [
                'parent:id,name',
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

        return [$teamFolder, $folders, $files, $totalEntries];
    }
}
