<?php

namespace App\Http\Resources;

use App\Models\Language;
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
        $current_language = Language::with('languageStrings')
            ->whereLocale(get_setting('language') ?? 'en')
            ->first();

        return [
            'data' => $this->collection,
            'meta' => [
                'current_language'     => new LanguageResource($current_language),
                'default_translations' => get_default_language_strings()
            ],
        ];
    }
}
