<?php
namespace Domain\Localization\Controllers;

use Illuminate\Http\Response;
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
    ): Response {
        // Abort in demo mode
        abort_if(is_demo(), 204, 'Done.');

        $language
            ->languageTranslations()
            ->where('key', $request->input('name'))
            ->update([
                'value' => $request->input('value'),
            ]);

        cache()->forget("language-translations-{$language->locale}");

        return response(
            'Done',
            204
        );
    }
}
