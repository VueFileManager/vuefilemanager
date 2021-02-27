<?php


namespace App\Services;


use Illuminate\Support\Facades\Storage;

class SetupService
{
    /**
     * Create default folders which application to process files.
     */
    public function create_directories()
    {
        $system_directories = [
            'avatars',
            'chunks',
            'system',
            'files',
            'temp',
            'zip',
        ];

        foreach ($system_directories as $directory) {

            // Create directory for local driver
            Storage::disk('local')
                ->makeDirectory($directory);

            // Create directory for external driver
            Storage::makeDirectory($directory);
        }
    }
}