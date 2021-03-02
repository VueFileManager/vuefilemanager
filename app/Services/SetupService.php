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
        collect(['avatars', 'chunks', 'system', 'files', 'temp', 'zip',])
            ->each(function ($directory) {

                // Create directory for local driver
                Storage::disk('local')
                    ->makeDirectory($directory);

                // Create directory for external driver
                Storage::makeDirectory($directory);
            });
    }
}