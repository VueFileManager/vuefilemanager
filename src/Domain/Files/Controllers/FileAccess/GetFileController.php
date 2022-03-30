<?php
namespace Domain\Files\Controllers\FileAccess;

use Gate;
use Domain\Files\Models\File;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Domain\Files\Actions\DownloadFileAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetFileController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
        private DownloadFileAction $downloadFile,
    ) {
    }

    public function __invoke(
        string $filename,
    ): Response|RedirectResponse|StreamedResponse {
        // Get requested file
        $file = File::withTrashed()
            ->where('basename', $filename)
            ->firstOrFail();

        // Check if user can download file
        if (! $file->user->canDownload()) {
            return response(userActionNotAllowedError(), 401);
        }

        // Check if user has privileges to download file
        if (! Gate::any(['can-edit', 'can-view'], [$file, null])) {
            return response(accessDeniedError(), 403);
        }

        // TODO: resolve video buffering

        // Store user download size
        ($this->recordDownload)(
            file_size: $file->filesize,
            user_id: $file->user_id,
        );

        return ($this->downloadFile)($file);
    }
}
