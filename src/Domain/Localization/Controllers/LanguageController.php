<?php
namespace Domain\Localization\Controllers;

use Illuminate\Http\JsonResponse;
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
    public function index(): JsonResponse
    {
        return response()->json(
            new LanguageCollection(
                Language::sortable(['created_at', 'DESC'])->get()
            )
        );
    }

    /**
     * Get all language strings for admin translate
     */
    public function show(
        Language $language
    ): JsonResponse {
        return response()->json(new LanguageResource($language));
    }

    /**
     * Create new language
     */
    public function store(
        CreateLanguageRequest $request
    ): JsonResponse {
        // Abort in demo mode
        if (is_demo()) {
            $language = Language::query()
                ->where('locale', 'en')
                ->first();

            return response()->json(
                new LanguageResource($language),
                201
            );
        }

        $language = Language::create([
            'name'   => $request->input('name'),
            'locale' => $request->input('locale'),
        ]);

        return response()->json(
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
    ): JsonResponse {
        // Abort in demo mode
        if (is_demo()) {
            return response()->json(
                new LanguageResource($language),
                201
            );
        }

        $language->update(
            make_single_input($request)
        );

        return response()->json(
            new LanguageResource($language),
            201
        );
    }

    /**
     * Delete the language with all children strings
     */
    public function destroy(
        Language $language
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The language was successfully deleted',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        if ($language->locale === 'en') {
            return response()->json([
                'type'    => 'error',
                'message' => "Sorry, you can't delete default language.",
            ], 422);
        }

        // If user try to delete language used as default,
        // then set en language as default
        if ($language->locale === get_settings('language')) {
            Setting::whereName('language')->first()
                ->update(['value' => 'en']);
        }

        $language->delete();

        return response()->json($message);
    }
}
