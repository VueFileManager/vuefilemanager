<?php


namespace Domain\Files\Controllers\FileAccess;


use App\Http\Controllers\Controller;
use Domain\Files\Models\File as UserFile;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetThumbnailController extends Controller
{
    /**
     * Get image thumbnail
     */
    public function __invoke(
        Request $request,
        string $filename,
    ): FileNotFoundException|StreamedResponse {

        $file = UserFile::withTrashed()
            ->whereUserId(Auth::id())
            ->whereThumbnail($filename)
            ->firstOrFail();

        return $this->helper->download_thumbnail_file($file, Auth::id());
    }
}