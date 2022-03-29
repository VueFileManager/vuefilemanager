<?php
namespace Domain\UploadRequest\Controllers\FileAccess;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Traffic\Actions\RecordDownloadAction;
use Illuminate\Contracts\Foundation\Application;
use Domain\Files\Actions\DownloadThumbnailAction;
use Illuminate\Contracts\Routing\ResponseFactory;
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
    ): Application|ResponseFactory|Response|StreamedResponse {
        // Get file
        $file = File::where('user_id', $uploadRequest->user_id)
            ->where('basename', substr($filename, 3))
            ->firstOrFail();

        // Store user download size
        ($this->recordDownload)(
            file_size: $file->filesize,
            user_id: $uploadRequest->user_id,
        );

        // Finally, download thumbnail
        return ($this->downloadThumbnail)($filename, $file);
    }
}
