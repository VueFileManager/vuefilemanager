<?php
namespace Domain\Files\Controllers\FileAccess;

use Gate;
use Illuminate\Http\Request;
use Domain\Files\Models\File;
use App\Http\Controllers\Controller;
use Domain\Files\Actions\DownloadThumbnailAction;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class GetThumbnailController extends Controller
{
    public function __construct(
        private DownloadThumbnailAction $downloadThumbnail,
    ) {
    }

    public function __invoke(
        Request $request,
        string $filename,
    ): FileNotFoundException | StreamedResponse {
        $file = File::withTrashed()
            ->where('basename', substr($filename, 3))
            ->firstOrFail();

        if (! Gate::any(['can-edit', 'can-view'], [$file, null])) {
            abort(403, 'Access Denied');
        }

        return ($this->downloadThumbnail)($filename, $file);
    }
}
