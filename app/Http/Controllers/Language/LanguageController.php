<?php

namespace App\Http\Controllers\Language;

use App\Http\Requests\Languages\UpdateLanguageRequest;
use App\Http\Requests\Languages\UpdateStringRequest;
use App\Http\Requests\Languages\CreateLanguageRequest;


use App\Language;
use App\LanguageString;
use App\Http\Tools\Demo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    /**
     * Get all languages
     *
     * @return string
     */
    public function get_languages ()
    {
        return Language::all();
    }

    /**
     * Get all language strings
     *
     * @param $language
     * @return string
     */
    public function get_language_strings($locale)
    {
       return Language::where('locale', $locale)->with('languageStrings')->first();
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

        // Create new language
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
     * @param $id
     * @return $language
     */
    public function update_language(UpdateLanguageRequest $request, $id)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }
        
        // Get language
        $language = Language::findOrFail($id);

        // Update language
        $language->update(make_single_input($request));

        // Return updated language
        return $language;
    }

    /**
     * Update strings for language
     *
     * @param UpdateStringRequest $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_string(UpdateStringRequest $request)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        // Get language
        $lang = Language::where('locale', $request->input('locale'))->first();

        foreach($request->input('language') as $language) 
        {

            // If key with lang already exist update, if no crate 
            LanguageString::updateOrCreate([
                'language_id' => $lang->id,
                'key'         => $language['key'],
                'lang'        => $lang->locale,
            ],[
                'value'       => $language['value']
            ]);
        }
        
        return response('Done', 204);
    }
}
