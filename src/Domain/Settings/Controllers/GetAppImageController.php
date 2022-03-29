<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetAppImageController
{
    /**
     * Get system image
     */
    public function __invoke(
        string $basename
    ): StreamedResponse | Response {
        // Check if file exist
        if (! Storage::exists("/system/$basename")) {
            return response('File not found', 404);
        }

        // Return avatar
        return Storage::download("/system/$basename", $basename);
    }
}
