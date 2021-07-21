<?php
namespace Domain\Files\Actions;

use Domain\Files\Models\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadThumbnailAction
{
    /**
     * Get image thumbnail for browser
     */
    public function __invoke(
        File $file,
        string $user_id
    ): StreamedResponse {
        // Get file path
        $path = "/files/$user_id/{$file->getRawOriginal('thumbnail')}";

        // Check if file exist
        if (! Storage::exists($path)) {
            abort(404);
        }

        // Return image thumbnail
        return Storage::download($path, $file->getRawOriginal('thumbnail'));
    }
}
