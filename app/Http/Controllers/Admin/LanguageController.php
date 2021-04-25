<?php
namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\Language;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\LanguageCollection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Requests\Languages\UpdateStringRequest;
use App\Http\Requests\Languages\CreateLanguageRequest;
use App\Http\Requests\Languages\UpdateLanguageRequest;

class LanguageController extends Controller
{
    /**
     * Get all languages for admin translate
     *
     * @return array|Application|ResponseFactory|Response
     */
    public function get_languages()
    {
        return response(
            new LanguageCollection(Language::sortable(['created_at', 'DESC'])->get()),
            200
        );
    }

    /**
     * Get all language strings for admin translate
     *
     * @param Language $language
     */
    public function get_language(Language $language)
    {
        return response(
            new LanguageResource($language),
            200
        );
    }

    /**
     * Create new language
     *
     * @param CreateLanguageRequest $request
     * @return string
     */
    public function create_language(CreateLanguageRequest $request)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $language = Language::create([
            'name' => $request->input('name'),
            'locale' => $request->input('locale'),
        ]);

        return response(
            new LanguageResource($language),
            201
        );
    }

    /**
     * Update language
     *
     * @param UpdateLanguageRequest $request
     * @param Language $language
     */
    public function update_language(UpdateLanguageRequest $request, Language $language)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $language->update(make_single_input($request));

        return response(
            new LanguageResource($language),
            201
        );
    }

    /**
     * Update string for language
     *
     * @param UpdateStringRequest $request
     * @param Language $language
     * @return Application|ResponseFactory|Response
     */
    public function update_string(UpdateStringRequest $request, Language $language)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $language
            ->languageTranslations()
            ->where('key', $request->name)
            ->update([
                'value' => $request->value,
            ]);

        cache()->forget("language-translations-{$language->locale}");

        return response(
            'Done',
            204
        );
    }

    /**
     * Delete the language with all children strings
     *
     * @param Language $language
     * @return ResponseFactory|Response
     */
    public function delete_language(Language $language)
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if ($language->locale === 'en') {
            abort(401, "Sorry, you can't delete default language.");
        }

        // If user try to delete language used as default,
        // then set en language as default
        if ($language->locale === get_setting('language')) {
            Setting::whereName('language')->first()
                ->update(['value' => 'en']);
        }

        $language->delete();

        return response(
            'Done',
            204
        );
    }
}
