<?php
namespace Domain\Maintenance\Controllers;

use Gate;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Localization\Services\LanguageService;

class UpgradeTranslationsController extends Controller
{
    /**
     * Get new language translations from default translations
     * and insert it into database
     */
    public function __invoke(): Response
    {
        // Check admin permission
        Gate::authorize('maintenance');

        resolve(LanguageService::class)
            ->upgrade_language_translations();

        return response('Done.', 201);
    }
}
