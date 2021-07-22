<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CreateImageThumbnailAction
{
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
        string $file_path,
        string $filename,
        string $user_id
    ): string | null {
        $mimeType = Storage::disk('local')->mimeType($file_path);

        // Create thumbnail from image
        if (in_array($mimeType, $this->availableFormats)) {
            // Get thumbnail name
            $thumbnail = "thumbnail-$filename";

            // Create intervention image
            $image = Image::make(Storage::disk('local')
                ->path($file_path))
                ->orientate();

            // Resize image
            $image->resize(512, null, fn ($constraint) => $constraint->aspectRatio())->stream();

            // Store thumbnail to disk
            Storage::put("files/$user_id/$thumbnail", $image);
        }

        // Return thumbnail as svg file
        if ($mimeType === 'image/svg+xml') {
            return $filename;
        }

        return $thumbnail ?? null;
    }
}
