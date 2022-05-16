<?php
namespace Domain\UploadRequest\Controllers\FileAccess;

use Domain\Files\Models\File;
use Illuminate\Http\RedirectResponse;
use Domain\Files\Actions\DownloadFileAction;
use Domain\UploadRequest\Models\UploadRequest;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __invoke(
        string $filename,
        UploadRequest $uploadRequest,
    ): StreamedResponse|RedirectResponse {
        // Get file
        $file = File::where('user_id', $uploadRequest->user_id)
            ->where('basename', $filename)
            ->firstOrFail();

        // Store user download size
        ($this->recordDownload)(
            file_size: $file->filesize,
            user_id: $uploadRequest->user_id,
        );

        // Finally, download file
        return ($this->downloadFile)($file);
    }
}
