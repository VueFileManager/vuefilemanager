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
        string $filename,
        File $file
    ): StreamedResponse {
        // Get file path
        $path = "/files/$file->user_id/$filename";

        // Check if file exist
        if (! Storage::exists($path)) {
            // Get original file path
            $substituteFilePath = "/files/$file->user_id/$file->basename";

            // Check if original file exist
            if (! Storage::exists($substituteFilePath)) {
                abort(404);
            }

            // Return image thumbnail
            return Storage::download($substituteFilePath, $filename);
        }

        // Return image thumbnail
        return Storage::download($path, $filename);
    }
}
