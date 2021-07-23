<?php
namespace Domain\Localization\Resources;

use Domain\Localization\Models\Language;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LanguageCollection extends ResourceCollection
{
    public $collects = LanguageResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $current_language = Language::with('languageTranslations')
            ->whereLocale(get_settings('language') ?? 'en')
            ->first();

        return [
            'data' => $this->collection,
            'meta' => [
                'current_language'       => new LanguageResource($current_language),
                'reference_translations' => get_default_language_translations(),
            ],
        ];
    }
}
