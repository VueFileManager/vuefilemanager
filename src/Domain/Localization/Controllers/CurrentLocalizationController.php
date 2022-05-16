<?php
namespace Domain\Localization\Controllers;

use Illuminate\Http\JsonResponse;
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
    ): JsonResponse {
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

        $appTranslations = $translations
            ? map_language_translations($translations)
            : get_default_language_translations();

        return response()->json($appTranslations);
    }
}
