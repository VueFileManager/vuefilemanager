<?php
namespace Domain\Settings\Actions;

use Domain\Settings\Models\Setting;
use Domain\Maintenance\Models\AppUpdate;

class SeedDefaultSettingsAction
{
    /**
     * Store default VueFileManager settings into database
     */
    public function __invoke(): void
    {
        // Set default settings
        collect(
            config('content.content')
        )->each(fn ($content) => Setting::updateOrCreate([
            'name' => $content['name'],
        ], [
            'value' => $content['value'],
        ]));

        // Set update records
        collect(config('vuefilemanager.updates'))
            ->each(fn ($version) => AppUpdate::create(['version' => $version]));
    }
}
