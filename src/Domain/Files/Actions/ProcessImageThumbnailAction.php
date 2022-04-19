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
        string $name,
        string $userId,
    ): void {
        // Get local disk instance
        $disk = Storage::disk('local');

        if (! in_array($disk->mimeType("files/$userId/$name"), $this->availableFormats)) {
            return;
        }

        // Make copy of file for the thumbnail generation
        $disk->copy("files/$userId/$name", "temp/$userId/$name");

        // Create thumbnails instantly
        ($this->generateImageThumbnail)(
            fileName: $name,
            userId: $userId,
            execution: 'immediately'
        );

        // Create thumbnails later
        ($this->generateImageThumbnail)
            ->onQueue('high')
            ->execute(
                fileName: $name,
                userId: $userId,
                execution: 'later'
            );
    }
}
