<?php
namespace Domain\Zip\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Zip\Actions\ZipFilesAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Guest download multiple files via zip
 */
class VisitorZipFilesController extends Controller
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
        private ZipFilesAction $zipFiles,
    ) {
    }

    public function __invoke(
        Request $request,
        Share $shared,
    ): Response {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        $file_parent_folders = File::whereUserId($shared->user_id)
            ->whereIn('id', $request->items)
            ->get()
            ->pluck('folder_id')
            ->toArray();

        // Check access to requested directory
        ($this->verifyAccessToItem)($file_parent_folders, $shared);

        // Get requested files
        $files = File::whereUserId($shared->user_id)
            ->whereIn('id', $request->items)
            ->get();

        $zip = ($this->zipFiles)($files, $shared);

        // Get file
        return response([
            'url'  => url("/zip/{$zip->id}/public/{$shared->token}"),
            'name' => $zip->basename,
        ], 201);
    }
}
