<?php
namespace Domain\UploadRequest\Controllers\FileAccess;

use Domain\Files\Models\File;
use Illuminate\Http\Response;
use Domain\Files\Actions\DownloadFileAction;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Traffic\Actions\RecordDownloadAction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Get shared file record
 */
class GetFileFromUploadRequestController
{
    public function __construct(
        private DownloadFileAction $downloadFile,
        private RecordDownloadAction $recordDownload,
    ) {
    }

    public function __invoke(
        string $filename,
        UploadRequest $uploadRequest
    ): Application|ResponseFactory|Response|BinaryFileResponse {
        // Get file
        $file = File::where('user_id', $uploadRequest->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Store user download size
        ($this->recordDownload)(
            file_size: (int) $file->getRawOriginal('filesize'),
            user_id: $uploadRequest->user_id,
        );

        // Finally, download file
        return ($this->downloadFile)($file, $uploadRequest->user_id);
    }
}
