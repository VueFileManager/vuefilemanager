<?php
namespace Domain\Files\Controllers\FileAccess;

use Gate;
use App\Http\Controllers\Controller;
use Domain\Files\Models\File as UserFile;
use Domain\Files\Actions\DownloadFileAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GetFileController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
        private DownloadFileAction $downloadFile,
    ) {}

    public function __invoke(
        string $filename,
    ): BinaryFileResponse {

        $file = UserFile::withTrashed()
            ->where('basename', $filename)
            ->firstOrFail();

        if (! Gate::any(['can-edit', 'can-view'], [$file, null])) {
            abort(403, 'Access Denied');
        }

        // Store user download size
        ($this->recordDownload)(
            file_size: (int) $file->getRawOriginal('filesize'),
            user_id: $file->user_id,
        );

        return ($this->downloadFile)($file, $file->user_id);
    }
}
