<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;

class ProcessImageThumbnailAction
{
    public function __construct(
        public GenerateImageThumbnailAction $generateImageThumbnail,
    ) {
    }

    private array $availableFormats = [
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/webp',
    ];

    /**
     * Create image thumbnail from uploaded image
     */
    public function __invoke(
        string $fileName,
        $file,
        string $userId
    ): void {
        // Create thumbnail from image
        if (in_array($file->getClientMimeType(), $this->availableFormats)) {
            // Make copy of file for the thumbnail generation
            Storage::disk('local')->copy("files/$userId/{$fileName}", "temp/$userId/{$fileName}");

            // Create thumbnails instantly
            ($this->generateImageThumbnail)(
                fileName: $fileName,
                userId: $userId,
                execution: 'immediately'
            );

            // Create thumbnails later
            ($this->generateImageThumbnail)->onQueue('high')->execute(
                fileName: $fileName,
                userId: $userId,
                execution: 'later'
            );
        }
    }
}
