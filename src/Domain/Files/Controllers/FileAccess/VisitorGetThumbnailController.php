<?php
namespace Domain\Files\Controllers\FileAccess;

use Domain\Sharing\Models\Share;
use App\Http\Controllers\Controller;
use Domain\Files\Models\File as UserFile;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\Files\Actions\DownloadThumbnailAction;
use Domain\Sharing\Actions\ProtectShareRecordAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Domain\Sharing\Actions\VerifyAccessToItemWithinAction;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

/**
 * Get public image thumbnail
 */
class VisitorGetThumbnailController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
        private DownloadThumbnailAction $downloadThumbnail,
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
    ): StreamedResponse {
        // Check ability to access protected share files
        ($this->protectShareRecord)($shared);

        // Get file record
        $file = UserFile::where('user_id', $shared->user_id)
            ->where('basename', substr($filename, 3))
            ->firstOrFail();

        // Check file access
        ($this->verifyAccessToItemWithin)($shared, $file);

        // Store user download size
        ($this->recordDownload)(
            file_size: $file->filesize,
            user_id: $shared->user_id,
        );

        // Finally, download thumbnail
        return ($this->downloadThumbnail)($filename, $file);
    }
}
