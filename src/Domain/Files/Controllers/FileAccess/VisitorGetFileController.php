<?php
namespace Domain\Files\Controllers\FileAccess;

use Domain\Files\Models\File;
use Domain\Sharing\Models\Share;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Files\Actions\DownloadFileAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Domain\Sharing\Actions\VerifyAccessToItemWithinAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Get file public
 */
class VisitorGetFileController extends Controller
{
    public function __construct(
        private DownloadFileAction $downloadFile,
        private RecordDownloadAction $recordDownload,
        private ProtectShareRecordAction $protectShareRecord,
        private VerifyAccessToItemWithinAction $verifyAccessToItemWithin,
    ) {
    }

    /**
     * @throws FileNotFoundException
     */
    public function __invoke(
        $filename,
        Share $shared,
    ): StreamedResponse|RedirectResponse|JsonResponse {
        // Check if user can download file
        if (! $shared->user->canDownload()) {
            return response()->json(userActionNotAllowedError(), 401);
        }

        // Check ability to access protected share files
        ($this->protectShareRecord)($shared);

        // Get file record
        $file = File::where('user_id', $shared->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Check file access
        ($this->verifyAccessToItemWithin)($shared, $file);

        // Store user download size
        ($this->recordDownload)(
            file_size: $file->filesize,
            user_id: $shared->user_id,
        );

        // Finally, download file
        return ($this->downloadFile)($file);
    }
}
