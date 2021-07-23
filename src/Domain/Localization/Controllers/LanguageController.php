<?php
namespace Domain\Localization\Controllers;

use Illuminate\Http\Response;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;
use Domain\Localization\Models\Language;
use Domain\Localization\Resources\LanguageResource;
use Domain\Localization\Resources\LanguageCollection;
use Domain\Localization\Requests\CreateLanguageRequest;
use Domain\Localization\Requests\UpdateLanguageRequest;

class LanguageController extends Controller
{
    /**
     * Get all languages for admin translate
     */
    public function index(): Response
    {
        return response(
            new LanguageCollection(
                Language::sortable(['created_at', 'DESC'])->get()
            ),
            200
        );
    }

    /**
     * Get all language strings for admin translate
     */
    public function show(Language $language): Response
    {
        return response(
            new LanguageResource($language),
            200
        );
    }

    /**
     * Create new language
     */
    public function store(CreateLanguageRequest $request): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $language = Language::create([
            'name'   => $request->input('name'),
            'locale' => $request->input('locale'),
        ]);

        return response(
            new LanguageResource($language),
            201
        );
    }

    /**
     * Update language
     */
    public function update(
        UpdateLanguageRequest $request,
        Language $language
    ): Response {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $language->update(
            make_single_input($request)
        );

        return response(
            new LanguageResource($language),
            201
        );
    }

    /**
     * Delete the language with all children strings
     */
    public function destroy(Language $language): Response
    {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        if ($language->locale === 'en') {
            return response("Sorry, you can't delete default language.", 401);
        }

        // If user try to delete language used as default,
        // then set en language as default
        if ($language->locale === get_settings('language')) {
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
