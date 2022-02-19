<?php
namespace Domain\UploadRequest\Controllers\FileAccess;

use App\Http\Controllers\Controller;
use Domain\Files\Actions\DownloadThumbnailAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Files\Models\File;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Get shared file record
 */
class GetThumbnailFromUploadRequestController extends Controller
{
    public function __construct(
        private DownloadThumbnailAction $downloadThumbnail,
        private RecordDownloadAction $recordDownload,
    ) {
    }

    public function __invoke(
        string $filename,
        UploadRequest $uploadRequest
    ): StreamedResponse {
        $originalFileName = substr($filename, 3);

        // Get file
        $file = File::where('user_id', $uploadRequest->user_id)
            ->where('basename', $originalFileName)
            ->firstOrFail();

        // Store user download size
        ($this->recordDownload)(
            file_size: (int) $file->getRawOriginal('filesize'),
            user_id: $uploadRequest->user_id,
        );

        // Finally, download thumbnail
        return ($this->downloadThumbnail)($filename, $file);
    }
}
