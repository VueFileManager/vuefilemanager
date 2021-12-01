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
    public function execute($file_name, $user_id, $thumnails_sizes)
    {
       
        // Get image from disk
        $image = Storage::disk('local')->get("temp/$user_id/{$file_name}");

        // Create intervention image
        $intervention = Image::make($image)->orientate();

        collect($thumnails_sizes)
            ->each(function ($size) use ($intervention, $user_id, $file_name) {

                dd($intervention->getWidth());

                if ($intervention->getWidth() > $size['size']) {

                    // Generate thumbnail
                    $intervention->resize($size['size'], null, fn ($constraint) => $constraint->aspectRatio())->stream();
    
                    // Store thumbnail to disk
                    Storage::put("files/$user_id/{$size['name']}-{$file_name}", $intervention);
                }
            });

    }
}
