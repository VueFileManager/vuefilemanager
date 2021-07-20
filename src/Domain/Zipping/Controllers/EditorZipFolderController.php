<?php


namespace Domain\Zipping\Controllers;


use App\Http\Controllers\Controller;
use Domain\Folders\Models\Folder;
use Domain\Sharing\Models\Share;
use Domain\Zipping\Actions\ZipFolderAction;
use Illuminate\Http\Response;
use Support\Services\HelperService;

/**
 * Guest download folder via zip
 */
class EditorZipFolderController extends Controller
{
    public function __construct(
        public HelperService $helper,
    ) {}

    public function __invoke(
        ZipFolderAction $zipFolder,
        string $id,
        Share $shared,
    ): Response {
        // Check ability to access protected share record
        $this->helper->check_protected_share_record($shared);

        // Check access to requested folder
        $this->helper->check_item_access($id, $shared);

        // Get folder
        $folder = Folder::whereUserId($shared->user_id)
            ->where('id', $id);

        if (! $folder->exists()) {
            abort(404, 'Requested folder doesn\'t exists.');
        }

        $zip = ($zipFolder)($id, $shared);

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