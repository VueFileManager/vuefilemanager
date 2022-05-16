<?php
namespace Domain\Files\Actions;

use Domain\Files\Models\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class DownloadThumbnailAction
{
    /**
     * Get image thumbnail for browser
     *
     * @throws FileNotFoundException
     */
    public function __invoke(
        string $filename,
        File $file
    ): StreamedResponse {
        // Get file path
        $filePath = "/files/$file->user_id/$filename";

        // Check if file exist
        if (! Storage::exists($filePath)) {
            // Get original file path
            $substituteFilePath = "/files/$file->user_id/$file->basename";

            // Check if original file exist
            if (! Storage::exists($substituteFilePath)) {
                throw new FileNotFoundException();
            }

            // Return image thumbnail
            return Storage::download($substituteFilePath, $filename);
        }

        // Return image thumbnail
        return Storage::download($filePath, $filename);
    }
}
