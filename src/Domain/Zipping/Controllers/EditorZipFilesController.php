<?php
namespace Domain\Zipping\Controllers;

use Illuminate\Http\Request;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use Support\Services\HelperService;
use App\Http\Controllers\Controller;
use Domain\Zipping\Actions\ZipFilesAction;

/**
 * Guest download multiple files via zip
 */
class EditorZipFilesController extends Controller
{
    public function __construct(
        public HelperService $helper,
    ) {
    }

    public function __invoke(
        ZipFilesAction $zipFiles,
        Request $request,
        Share $shared,
    ): Response {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        $file_parent_folders = File::whereUserId($shared->user_id)
            ->whereIn('id', $request->items)
            ->get()
            ->pluck('folder_id')
            ->toArray();

        // Check access to requested directory
        $this->helper->check_item_access($file_parent_folders, $shared);

        // Get requested files
        $files = File::whereUserId($shared->user_id)
            ->whereIn('id', $request->items)
            ->get();

        $zip = ($zipFiles)($files, $shared);

        // Get file
        return response([
            'url' => route('zip_public', [
                'id'    => $zip->id,
                'token' => $shared->token,
            ]),
            'name' => $zip->basename,
        ], 201);
    }
}
