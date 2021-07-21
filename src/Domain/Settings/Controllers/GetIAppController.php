<?php


namespace Domain\Settings\Controllers;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetIAppController
{
    /**
     * Get system image
     */
    public function __invoke(
        string $basename
    ): StreamedResponse|FileNotFoundException {

        // Check if file exist
        if (! Storage::exists("/system/$basename")) {
            abort(404);
        }

        // Return avatar
        return Storage::download("/system/$basename", $basename);
    }
}