<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;
use Intervention\Image\ImageManagerStatic as Image;

class GenerateImageThumbnailAction
{
    use QueueableAction;

    public function __invoke($fileName, $userId, $execution): void
    {
        $localDisk = Storage::disk('local');

        // Get image from disk
        $image = $localDisk->get("temp/$userId/$fileName");

        // Get image width
        $imageWidth = getimagesize($localDisk->path("temp/$userId/$fileName"))[0];

        collect(config("vuefilemanager.image_sizes.$execution"))
            ->each(function ($size) use ($image, $userId, $fileName, $imageWidth) {
                if ($imageWidth > $size['size']) {
                    // Create intervention image
                    $intervention = Image::make($image)->orientate();

                    // Generate thumbnail
                    $intervention->resize($size['size'], null, fn ($constraint) => $constraint->aspectRatio())->stream();

                    // Store thumbnail to disk
                    Storage::put("files/$userId/{$size['name']}-$fileName", $intervention);
                }
            });

        // Delete file after generate a thumbnail
        if ($execution === 'later') {
            Storage::disk('local')->delete("temp/$userId/$fileName");
        }
    }
}
