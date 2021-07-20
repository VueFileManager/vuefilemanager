<?php
namespace Domain\Localization\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Domain\Localization\Models\Language;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrentLocalizationController
{
    /**
     * Get language translations for frontend app
     */
    public function __invoke(
        string $lang
    ): Collection {
        $translations = cache()
            ->rememberForever("language-translations-$lang", function () use ($lang) {
                try {
                    return Language::whereLocale($lang)
                        ->firstOrFail()
                        ->languageTranslations;
                } catch (QueryException | ModelNotFoundException $e) {
                    return null;
                }
            });

        return $translations
            ? map_language_translations($translations)
            : get_default_language_translations();
    }
}
