<?php
namespace Domain\SetupWizard\Services;

use Domain\Pages\Models\Page;
use Domain\Settings\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Domain\Localization\Models\Language;

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
    public function seed_default_pages()
    {
        collect(config('content.pages'))
            ->each(function ($page) {
                Page::updateOrCreate($page);
            });
    }

    /**
     * Store default VueFileManager settings into database
     *
     * @param $license
     */
    public function seed_default_settings($license)
    {
        collect(config('content.content.' . strtolower($license)))
            ->each(function ($content) {
                Setting::forceCreate($content);
            });
    }

    /**
     * Store default VueFileManager settings into database
     */
    public function seed_default_language()
    {
        Language::create([
            'name'   => 'English',
            'locale' => 'en',
        ]);

        Setting::create([
            'name'  => 'language',
            'value' => 'en',
        ]);
    }
}
