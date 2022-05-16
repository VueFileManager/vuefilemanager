<?php
namespace App\Users\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetAvatarController
{
    /**
     * Get user avatar
     */
    public function __invoke(
        string $basename
    ): StreamedResponse {
        // Check if file exist
        if (! Storage::exists("/avatars/$basename")) {
            throw new ModelNotFoundException();
        }

        // Return avatar
        return Storage::download("/avatars/$basename", $basename);
    }
}
