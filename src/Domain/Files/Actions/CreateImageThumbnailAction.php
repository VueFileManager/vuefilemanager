<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;
use Intervention\Image\ImageManagerStatic as Image;
use Domain\Files\Actions\CreateImageThumbnailAcionQueue;


class CreateImageThumbnailAction
{
    use QueueableAction;
    
    public function __construct(
        CreateImageThumbnailAcionQueue  $action,
    )
    {
        $this->action = $action;
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
        string $file_name,
        $file,
        string $user_id
    ): void {

        // Create thumbnail from image
        if (in_array($file->getClientMimeType(), $this->availableFormats)) {

            // Make copy of file for the thumbnail generation
            Storage::disk('local')->copy("files/$user_id/{$file_name}", "temp/$user_id/{$file_name}");
            
            // Create intervention image
            $intervention = Image::make($file)->orientate();

            // Generate avatar sizes
            collect(config('vuefilemanager.image_sizes.execute') )
                ->each(function ($size) use ($intervention, $file_name, $user_id) {

                    // Create thumbnail only if image is larger than predefined image sizes
                    if ($intervention->getWidth() > $size['size']) {

                            // Create thumbnail instantly
                            $this->action->execute($size, $file_name, $user_id, null);
                    }
                });

            $last = last(config('vuefilemanager.image_sizes.queue'));
    
            collect(config('vuefilemanager.image_sizes.queue'))
            ->each(function ($size) use ($intervention, $file_name, $user_id, $last) {

                $is_last = $last['size'] === $size['size'] ? true : false;

                // Create thumbnail only if image is larger than predefined image sizes
                if ($intervention->getWidth() > $size['size']) {

                        // Create thumbnail queue job
                        $this->action->onQueue()->execute($size, $file_name, $user_id, $is_last);
                                        
                } else {

                    // Delete file after generate a thumbnail
                    Storage::disk('local')->delete("temp/$user_id/{$file_name}");
                }
                
            });

            
        }
    }
}
