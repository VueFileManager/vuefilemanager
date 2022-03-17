<?php
namespace Domain\SetupWizard\Actions;

use Illuminate\Support\Facades\Storage;

class CreateDiskDirectoriesAction
{
    /**
     * Create default folders which application to process files.
     */
    public function __invoke(): void
    {
        collect(['avatars', 'chunks', 'system', 'files', 'temp'])
            ->each(function ($directory) {
                // Create directory for local driver
                Storage::disk('local')
                    ->makeDirectory($directory);

                // Create directory for external driver
                Storage::makeDirectory($directory);
            });
    }
}
