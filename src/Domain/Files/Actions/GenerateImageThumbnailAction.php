<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;

class GenerateImageThumbnailAction
{
    use QueueableAction;

    public function __invoke($fileName, $userId, $execution): void
    {
        // Get image width
        $imageWidth = getimagesize(
            Storage::disk('local')->path("temp/$userId/$fileName")
        )[0];

        collect(config("vuefilemanager.image_sizes.$execution"))
            ->each(function ($size) use ($userId, $fileName, $imageWidth) {
                if ($imageWidth > $size['size']) {
                    // Create intervention image (EXIF orientation is applied automatically)
                    $intervention = image_manager()->decodePath(
                        Storage::disk('local')->path("temp/$userId/$fileName")
                    );

                    // Generate thumbnail
                    $thumbnail = $intervention
                        ->scale(width: $size['size'])
                        ->encode();

                    // Store thumbnail to disk
                    Storage::put("files/$userId/{$size['name']}-$fileName", (string) $thumbnail);
                }
            });

        // Delete file after generate a thumbnail
        if ($execution === 'later') {
            Storage::disk('local')->delete("temp/$userId/$fileName");
        }
    }
}
