<?php
namespace Domain\Sharing\Controllers;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Files\Actions\DownloadFileAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Domain\Sharing\Actions\VerifyAccessToItemWithinAction;

class DirectlyDownloadFileController extends Controller
{
    public function __construct(
        private DownloadFileAction $downloadFile,
        private RecordDownloadAction $recordDownload,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemWithinAction $verifyAccessToItemWithin,
    ) {
    }

    public function __invoke(
        Share $share
    ): Response|StreamedResponse|RedirectResponse {
        // Check if item is not a folder
        if ($share->type !== 'file') {
            return response('This content is not downloadable');
        }

        // Check ability to access protected share files
        ($this->protectShareRecord)($share);

        // Check if user can download file
        if (! $share->user->canDownload()) {
            return response([
                'type'    => 'error',
                'message' => 'This user action is not allowed.',
            ], 401);
        }

        // Get file record
        $file = File::where('user_id', $share->user_id)
            ->where('id', $share->item_id)
            ->firstOrFail();

        // Check file access
        ($this->verifyAccessToItemWithin)($share, $file);

        // Store user download size
        ($this->recordDownload)(
            file_size: $file->filesize,
            user_id: $share->user_id,
        );

        return ($this->downloadFile)($file);
    }
}
