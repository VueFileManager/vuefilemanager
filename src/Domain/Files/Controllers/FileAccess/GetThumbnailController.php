<?php
namespace Domain\Files\Controllers\FileAccess;

use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Domain\Files\Models\File as UserFile;
use Domain\Files\Actions\DownloadThumbnailAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GetThumbnailController extends Controller
{
    public function __construct(
        private DownloadThumbnailAction $downloadThumbnail,
    ) {}

    public function __invoke(
        Request $request,
        string $filename,
    ): FileNotFoundException | StreamedResponse {

        $file = UserFile::withTrashed()
            ->where('thumbnail', $filename)
            ->firstOrFail();

        if (! Gate::any(['can-edit', 'can-view'], [$file, null])) {
            abort(403, 'Access Denied');
        }

        return ($this->downloadThumbnail)($file, $file->user_id);
    }
}
