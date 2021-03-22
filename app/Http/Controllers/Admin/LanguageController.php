<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use App\Language;
use App\LanguageString;


use App\Http\Tools\Demo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Languages\UpdateStringRequest;
use App\Http\Requests\Languages\CreateLanguageRequest;
use App\Http\Requests\Languages\UpdateLanguageRequest;

class LanguageController extends Controller
{

    /**
     * Get all languages for admin translate
     * 
     * @return Collection
     */
    public function get_languages()
    {
        return [
           'languages'    => Language::all(),
           'set_language' => Setting::whereName('language')->first()->value
        ];
    }

    /**
     * Get all language strings for admin translate
     *
     * @param Language $language
     * @return Collection
     */
    public function get_language_strings(Language $language)
    {
        $lang = Language::whereId($language->id);

        $strings = $lang->with('languageStrings')->first();

        // dd($language);

        $license = get_setting('license') === 'Extended' ? 'extended' : 'regular';

        $default_strings = collect(config('language_strings.' . $license));

        return [
            'translated_strings' => $strings,
            'default_strings'    => $default_strings
        ];
    }

    /**
     * Create new language
     *
     * @param CreateLanguageRequest $request
     * @return string
     */
    public function create_language(CreateLanguageRequest $request)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        // Create languages & strings
        $language = Language::create([
            'name'      => $request->name,
            'locale'    => $request->locale
        ]);

        // Return created language
        return $language;
    }

    /**
     * Update language
     *
     * @param UpdateLanguageRequest $request
     * @param Language $language
     * @return $language
     */
    public function update_language(UpdateLanguageRequest $request, Language $language)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        // Update language
        $language->update(make_single_input($request));

        // Return updated language
        return $language;
    }

    /**
     * Update string for language
     *
     * @param UpdateStringRequest $request
     * @param Language $language
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_string(UpdateStringRequest $request, Language $language)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        LanguageString::whereLangAndKey($language->locale, $request->name)
            ->update([
                'key'         => $request->name,
                'lang'        => $language->locale,
                'value'       => $request->value
            ]);

        
        Cache::forget('language_strings-' . $language->locale);

        return response('Done', 204);
    }

    /**
     * Delete the language with all children strings
     *
     * @param Language $language
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function delete_language(Language $language)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        $language->delete();

        return response('Done', 204);
    }
}
