<?php
namespace Domain\Maintenance\Controllers;

use Gate;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Domain\Localization\Actions\UpgradeLanguageTranslationsAction;

class UpgradeTranslationsController extends Controller
{
    public function __construct(
        public UpgradeLanguageTranslationsAction $upgradeLanguageTranslations,
    ) {
    }

    /**
     * Get new language translations from default translations
     * and insert it into database
     */
    public function __invoke(): Response
    {
        // Check admin permission
        Gate::authorize('maintenance');

        ($this->upgradeLanguageTranslations)();

        return response('Done.', 201);
    }
}
