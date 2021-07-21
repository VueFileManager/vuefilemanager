<?php


namespace App\Users\Controllers;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetAvatarController
{
    /**
     * Get user avatar
     */
    public function __invoke(
        string $basename
    ): StreamedResponse|FileNotFoundException {

        // Check if file exist
        if (! Storage::exists("/avatars/$basename")) {
            abort(404);
        }

        // Return avatar
        return Storage::download("/avatars/$basename", $basename);
    }
}