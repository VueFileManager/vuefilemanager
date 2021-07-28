<?php
namespace Domain\Zip\Controllers;

use STS\ZipStream\ZipStream;
use Domain\Sharing\Models\Share;
use Domain\Folders\Models\Folder;
use App\Http\Controllers\Controller;
use Domain\Zip\Actions\ZipFolderAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Domain\Sharing\Actions\VerifyAccessToItemAction;

/**
 * Guest download folder via zip
 */
class VisitorZipFolderController extends Controller
{
    public function __construct(
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemAction $verifyAccessToItem,
        private RecordDownloadAction $recordDownload,
        private ZipFolderAction $zipFolder,
    ) {
    }

    public function __invoke(
        string $id,
        Share $shared,
    ): ZipStream {
        // Check ability to access protected share record
        ($this->protectShareRecord)($shared);

        // Check access to requested folder
        ($this->verifyAccessToItem)($id, $shared);

        // Get folder
        $folder = Folder::whereUserId($shared->user_id)
            ->where('id', $id);

        if (! $folder->exists()) {
            abort(404, "Requested folder doesn't exists.");
        }

        // Create zip
        $zip = ($this->zipFolder)($id, $shared);

        ($this->recordDownload)(
            file_size: $zip->predictZipSize(),
            user_id: $shared->user_id,
        );

        return $zip;
    }
}
