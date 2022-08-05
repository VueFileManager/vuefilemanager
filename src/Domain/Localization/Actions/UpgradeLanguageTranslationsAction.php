<?php
namespace Domain\Localization\Actions;

use DB;
use Domain\Localization\Models\Language;
use Domain\Localization\Models\LanguageTranslation;

class UpgradeLanguageTranslationsAction
{
    /**
     * Find newly added translations in default language
     * translations file and insert it into database
     */
    public function __invoke(): void
    {
        // Get all app locales
        $locales = Language::all()
            ->pluck('locale');

        // Get default translations
        $translations = LanguageTranslation::whereLang('en')
            ->get();

        // Find new translations in default translations
        $newbies = collect([
            config('language-translations.extended'),
            config('language-translations.regular'),
            config('custom-language-translations'),
        ])
            ->collapse()
            ->diffKeys(
                map_language_translations($translations)
            );

        // Store new translations for every language
        $locales->each(function ($locale) use ($newbies) {
            $translations = $newbies
                ->map(function ($value, $key) use ($locale) {
                    return [
                        'lang'  => $locale,
                        'value' => $value,
                        'key'   => $key,
                    ];
                })->toArray();

            $chunks = array_chunk($translations, 100);

            foreach ($chunks as $chunk) {
                // Store translations into database
                DB::table('language_translations')
                    ->insert($chunk);
            }

            // Flush cache
            cache()->forget("language-translations-$locale");
        });
    }
}
