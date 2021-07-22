<?php


namespace Domain\Localization\Actions;


use Domain\Localization\Models\Language;
use Domain\Settings\Models\Setting;

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