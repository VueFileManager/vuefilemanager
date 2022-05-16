<?php
namespace Domain\Localization\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Domain\Localization\Models\Language;
use Domain\Localization\Requests\UpdateStringRequest;

class UpdateLanguageStringController extends Controller
{
    /**
     * Update language string
     */
    public function __invoke(
        UpdateStringRequest $request,
        Language $language,
    ): JsonResponse {
        $message = [
            'type'    => 'success',
            'message' => 'The language strings was successfully updated',
        ];

        if (is_demo()) {
            return response()->json($message);
        }

        $language
            ->languageTranslations()
            ->where('key', $request->input('name'))
            ->update([
                'value' => $request->input('value'),
            ]);

        cache()->forget("language-translations-{$language->locale}");

        return response()->json($message);
    }
}
