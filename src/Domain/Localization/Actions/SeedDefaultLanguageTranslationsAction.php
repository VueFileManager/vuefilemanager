<?php
namespace Domain\Localization\Actions;

use DB;

class SeedDefaultLanguageTranslationsAction
{
    public function __invoke(
        string $license,
        string $locale
    ): void {
        $translations = [
            'extended' => collect([
                config('language-translations.extended'),
                config('language-translations.regular'),
                config('custom-language-translations'),
            ])->collapse(),
            'regular' => collect([
                config('language-translations.regular'),
                config('custom-language-translations'),
            ])->collapse(),
        ];

        $translations = $translations[strtolower($license)]
            ->map(fn ($value, $key) => [
                'lang'  => $locale,
                'value' => $value,
                'key'   => $key,
            ])->toArray();

        $chunks = array_chunk($translations, 100);

        foreach ($chunks as $chunk) {
            DB::table('language_translations')
                ->insert($chunk);
        }
    }
}
