<?php

namespace Domain\Files\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\QueueableAction\QueueableAction;
use Intervention\Image\ImageManagerStatic as Image;


class CreateImageThumbnailAcionQueue
{
    use QueueableAction;

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute($size, $file_name, $user_id, $is_last)
    {
        // Get image from disk
        $image = Storage::disk('local')->get("temp/$user_id/{$file_name}");

        // Create intervention image
        $intervention = Image::make($image)->orientate();
        
        // Generate thumbnail
        $intervention->resize($size['size'], null, fn ($constraint) => $constraint->aspectRatio())->stream();

        // Store thumbnail to disk
        Storage::put("files/$user_id/{$size['name']}-{$file_name}", $intervention);

        if ($is_last) {

            // Delete file after generate a thumbnail
            Storage::disk('local')->delete("temp/$user_id/{$file_name}");
        }

    }
}
