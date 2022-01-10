<?php
namespace Domain\Settings\Actions;

use Domain\Settings\Models\Setting;

class SeedDefaultSettingsAction
{
    /**
     * Store default VueFileManager settings into database
     */
    public function __invoke(
        string $license
    ): void {
        collect(
            config('content.content.' . strtolower($license))
        )->each(fn ($content) => Setting::updateOrCreate([
            'name' => $content['name'],
        ], [
            'value' => $content['value'],
        ]));
    }
}
