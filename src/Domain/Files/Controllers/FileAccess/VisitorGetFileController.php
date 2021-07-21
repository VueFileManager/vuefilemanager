<?php
namespace Domain\Files\Controllers\FileAccess;

use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Models\File as UserFile;
use Domain\Files\Actions\DownloadFileAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Get file public
 */
class VisitorGetFileController extends Controller
{
    public function __construct(
        private DownloadFileAction $downloadFile,
        private RecordDownloadAction $recordDownload,
    ) {
    }

    public function __invoke(
        $filename,
        Share $shared,
    ): BinaryFileResponse {
        // Check ability to access protected share files
        $this->helper->check_protected_share_record($shared);

        // Get file record
        $file = UserFile::where('user_id', $shared->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Check file access
        $this->helper->check_guest_access_to_shared_items($shared, $file);

        // Store user download size
        ($this->recordDownload)(
            file_size: (int) $file->getRawOriginal('filesize'),
            user_id: $shared->user_id,
        );

        return ($this->downloadFile)($file, $shared->user_id);
    }
}
