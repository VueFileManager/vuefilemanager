<?php
namespace App\Users\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GetAvatarController
{
    /**
     * Get user avatar
     */
    public function __invoke(
        string $basename
    ): StreamedResponse | Response {
        // Check if file exist
        if (! Storage::exists("/avatars/$basename")) {
            return response('File not found', 404);
        }

        // Return avatar
        return Storage::download("/avatars/$basename", $basename);
    }
}
