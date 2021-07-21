<?php
namespace Domain\Files\Controllers\FileAccess;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Domain\Files\Models\File as UserFile;
use Domain\Files\Actions\DownloadThumbnailAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GetThumbnailController extends Controller
{
    public function __construct(
        private DownloadThumbnailAction $downloadThumbnail,
    ) {
    }

    /**
     * Get image thumbnail
     */
    public function __invoke(
        Request $request,
        string $filename,
    ): FileNotFoundException | StreamedResponse {
        $file = UserFile::withTrashed()
            ->whereUserId(Auth::id())
            ->whereThumbnail($filename)
            ->firstOrFail();

        return ($this->downloadThumbnail)($file, Auth::id());
    }
}
