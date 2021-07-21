<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CreateImageThumbnailAction
{
    /**
     * Create image thumbnail from gif, jpeg, jpg, png, webp or svg
     */
    public function __invoke(
        string $file_path,
        string $filename,
        string $user_id
    ): string | null {
        $availableFormats = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        // Create thumbnail from image
        if (in_array(Storage::disk('local')->mimeType($file_path), $availableFormats)) {
            // Get thumbnail name
            $thumbnail = "thumbnail-$filename";

            // Create intervention image
            $image = Image::make(Storage::disk('local')->path($file_path))
                ->orientate();

            // Resize image
            $image->resize(512, null, function ($constraint) {
                $constraint->aspectRatio();
            })->stream();

            // Store thumbnail to disk
            Storage::put("files/$user_id/$thumbnail", $image);
        }

        // Return thumbnail as svg file
        if (Storage::disk('local')->mimeType($file_path) === 'image/svg+xml') {
            $thumbnail = $filename;
        }

        return $thumbnail ?? null;
    }
}
