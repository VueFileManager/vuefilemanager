<?php
namespace Domain\Localization\Actions;

use Domain\Settings\Models\Setting;
use Domain\Localization\Models\Language;

class SeedDefaultLanguageAction
{
    /**
     * Store default VueFileManager language into database
     */
    public function __invoke(): void
    {
        Language::updateOrCreate([
            'name'   => 'English',
        ], [
            'locale' => 'en',
        ]);

        Setting::updateOrCreate([
            'name'  => 'language',
        ], [
            'value' => 'en',
        ]);
    }
}
