<?php

namespace App\Http\Controllers\Language;

use App\Language;
use App\LanguageString;
use App\Http\Tools\Demo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function show_strings($language)
    {
       return Language::where('locale', $language)->with('languegeStrings')->first();
    }

    public function update(Request $request)
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        $lang = Language::where('locale', $request->input('locale'))->first();

        foreach($request->input('language') as $language)
        {

            // If key with lang already exist update, if no crate 
            LanguageString::updateOrCreate(['key'  => $language['key'],
                                            'lang' =>$lang->locale 
                                           ], [
                                                'language_id' => $lang->id,
                                                'value'       =>$language['value']
                                            ]);
        }
        
        return response('Done', 204);
    }

    public function all_languages ()
    {
        return Language::all();
    }
}
