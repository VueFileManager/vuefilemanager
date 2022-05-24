<?php
namespace Domain\Browsing\Controllers;

use DB;
use Domain\Files\Models\File;
use Illuminate\Http\JsonResponse;
use Domain\Files\Resources\FilesCollection;

class BrowseLatestFilesController
{
    public function __invoke(): JsonResponse
    {
        $entriesPerPage = config('vuefilemanager.paginate.perPage');

        $page = request()->has('page')
            ? request()->input('page')
            : 'all';

        $totalFiles = DB::table('files')
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->count();

        $getWith = [
            'parent:id,name',
            'shared:token,id,item_id,permission,is_protected,expire_in',
        ];

        // Get paginator data
        [$paginate, $links] = formatPaginatorMetadata($totalFiles);

        // Get all files
        if ($page === 'all') {
            $files = File::with($getWith)
                ->where('user_id', auth()->id())
                ->sortable(['created_at' => 'desc'])
                ->get();
        }

        // Get certain page
        if ($page !== 'all') {
            $files = File::with($getWith)
                ->where('user_id', auth()->id())
                ->sortable(['created_at' => 'desc'])
                ->skip($entriesPerPage * ($page - 1))
                ->take($entriesPerPage)
                ->get();
        }

        return response()->json([
            'data'  => new FilesCollection($files),
            'links' => $links,
            'meta'  => [
                'paginate' => $paginate,
            ],
        ]);
    }
}
