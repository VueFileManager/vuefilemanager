<?php
namespace Support\Upgrading\Actions;

use DB;
use Schema;
use Artisan;
use Domain\Maintenance\Models\AppUpdate;
use Support\Upgrading\Controllers\UpgradingVersionsController;
use Domain\Localization\Actions\UpgradeLanguageTranslationsAction;

class UpdateSystemAction extends UpgradingVersionsController
{
    public function __invoke(): void
    {
        ini_set('max_execution_time', -1);

        // Upgrade the language translations
        if ($this->shouldUpdateTranslations()) {
            resolve(UpgradeLanguageTranslationsAction::class)();
        }

        // Check if there are some version to upgrade
        $shouldUpgradeSystem = $this->shouldUpgradeSystem();

        // Upgrade the app
        if (! empty($shouldUpgradeSystem)) {
            foreach ($shouldUpgradeSystem as $version) {
                // Get method name
                $method = "upgrade_to_$version";

                if (method_exists($this, $method)) {
                    // Run update
                    $this->{$method}();

                    // Store update record
                    AppUpdate::create(['version' => $version]);
                }
            }

            // Clear config
            Artisan::call('config:clear');
        }
    }

    private function shouldUpgradeSystem(): array
    {
        // Get already updated versions
        $alreadyUpdated = Schema::hasTable('app_updates')
            ? AppUpdate::all()
                ->pluck('version')
                ->toArray()
            : [];

        // Get versions which has to be upgraded
        return array_diff(config('vuefilemanager.updates'), $alreadyUpdated);
    }

    private function shouldUpdateTranslations(): bool
    {
        $default_translations = collect([
            config('language-translations.extended'),
            config('language-translations.regular'),
            config('custom-language-translations'),
        ])->collapse();

        $originalTranslationCount = count($default_translations);

        $activeTranslationsCount = DB::table('language_translations')
            ->where('lang', 'en')
            ->count();

        return $activeTranslationsCount !== $originalTranslationCount;
    }
}
