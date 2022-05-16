<?php
namespace Domain\Settings\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetAppImageController
{
    /**
     * Get system image
     */
    public function __invoke(
        string $basename
    ): StreamedResponse {
        // Check if file exist
        if (! Storage::exists("/system/$basename")) {
            throw new ModelNotFoundException();
        }

        // Return avatar
        return Storage::download("/system/$basename", $basename);
    }
}
