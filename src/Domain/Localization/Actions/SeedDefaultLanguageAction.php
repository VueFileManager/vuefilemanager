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
