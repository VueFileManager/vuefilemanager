<?php

namespace App\Http\Controllers\Language;

use App\Language;
use App\LanguageString;
use App\Http\Tools\Demo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    /**
     * Create new language
     *
     * @param Request $request
     * @return string
     */
    public function create(Request $request) 
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
     * Get all language strings
     *
     * @param $language
     * @return string
     */
    public function get_language_strings($language)
    {
       return Language::where('locale', $language)->with('languegeStrings')->first();
    }
    
    /**
     * Update strings for language
     *
     * @param Request $request
     * @return ResponseFactory|\Illuminate\Http\Response
     */
    public function update_string(Request $request)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        $lang = Language::where('locale', $request->input('locale'))->first();

        // dd($lang->id);

        foreach($request->input('language') as $language) 
        {

            // If key with lang already exist update, if no crate 
            LanguageString::updateOrCreate([
                'language_id' => $lang->id,
                'key'  => $language['key'],
                'lang' => $lang->locale,
            ],[
                'value'       => $language['value']
            ]);
        }
        
        return response('Done', 204);
    }

    /**
     * Get all languages
     *
     * @return string
     */
    public function get_languages ()
    {
        return Language::all();
    }
}
