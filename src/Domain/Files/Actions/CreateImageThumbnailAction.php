<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        string $file_name,
        $file,
        string $user_id
    ): void {

        // Create thumbnail from image
        if (in_array($file->getClientMimeType(), $this->availableFormats)) {

            // Create intervention image
            $intervention = Image::make($file)->orientate();

            // Generate avatar sizes
            collect(config('vuefilemanager.image_sizes'))
                ->each(function ($size) use ($intervention, $file_name, $user_id) {

                    // Create thumbnail only if image is larger than predefined image sizes
                    if ($intervention->getWidth() > $size['size']) {

                        // Generate thumbnail
                        $intervention->resize($size['size'], null, fn ($constraint) => $constraint->aspectRatio())->stream();

                        // Store thumbnail to disk
                        Storage::put("files/$user_id/{$size['name']}-{$file_name}", $intervention);
                    }
                });
        }
    }
}
