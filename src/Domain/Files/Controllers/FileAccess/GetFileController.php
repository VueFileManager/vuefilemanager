<?php


namespace Domain\Files\Controllers\FileAccess;


use App\Http\Controllers\Controller;
use Domain\Files\Models\File as UserFile;
use Domain\Traffic\Actions\RecordDownloadAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetFileController extends Controller
{
    public function __construct(
        private RecordDownloadAction $recordDownload,
    ){}

    /**
     * Get file
     */
    public function __invoke(
        Request $request,
        string $filename,
    ): StreamedResponse {

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

        return $this->helper->download_file($file, Auth::id());
    }
}