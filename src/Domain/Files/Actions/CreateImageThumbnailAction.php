<?php
namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Domain\Files\Actions\CreateImageThumbnailAcionQueue;


class CreateImageThumbnailAction
{
    
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

            // Create thumbnail instantly
            $this->action->execute($file_name, $user_id, config('vuefilemanager.image_sizes.execute'));

            // Create thumbnail queue job
            $this->action->onQueue()->execute($file_name, $user_id, config('vuefilemanager.image_sizes.queue'));
        }
    }
}
