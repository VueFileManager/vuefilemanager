<?php
namespace Domain\Files\Controllers\FileAccess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Files\Models\File as UserFile;
use Domain\Files\Actions\DownloadFileAction;
use Domain\Traffic\Actions\RecordDownloadAction;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GetFileController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
        private DownloadFileAction $downloadFile,
    ) {
    }

    /**
     * Get file
     */
    public function __invoke(
        Request $request,
        string $filename,
    ): BinaryFileResponse {
        // Get file record
        $file = UserFile::withTrashed()
            ->where('user_id', Auth::id())
            ->where('basename', $filename)
            ->firstOrFail();

        // Store user download size
        ($this->recordDownload)(
            file_size: (int) $file->getRawOriginal('filesize'),
            user_id: Auth::id(),
        );

        return ($this->downloadFile)($file, Auth::id());
    }
}
