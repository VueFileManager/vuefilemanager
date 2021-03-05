<?php


namespace App\Services;


use App\Models\Page;
use Illuminate\Support\Facades\Storage;

class SetupService
{
    /**
     * Create default folders which application to process files.
     */
    public function create_directories()
    {
        collect(['avatars', 'chunks', 'system', 'files', 'temp', 'zip'])
            ->each(function ($directory) {

                // Create directory for local driver
                Storage::disk('local')
                    ->makeDirectory($directory);

                // Create directory for external driver
                Storage::makeDirectory($directory);
            });
    }

    /**
     * Store default pages content like Terms of Service, Privacy Policy and Cookie Policy into database
     */
    public function seed_pages()
    {
        collect(config('content.pages'))
            ->each(function ($page) {
                Page::updateOrCreate($page);
            });
    }
}