<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Domain\Settings\Models\Setting;

class GetSettingsValueController
{
    private array $blacklist = [
        'purchase_code',
        'license',
    ];

    /**
     * Get selected settings from public route
     */
    public function __invoke(
        Request $request
    ): Collection {
        if (str_contains($request->get('column'), '|')) {
            $columns = collect(explode('|', $request->get('column')))
                ->each(function ($column) {
                    if (in_array($column, $this->blacklist)) {
                        abort(401);
                    }
                });

            return Setting::whereIn('name', $columns)
                ->pluck('value', 'name');
        }

        if (in_array($request->get('column'), $this->blacklist)) {
            abort(401);
        }

        return Setting::where('name', $request->get('column'))
            ->pluck('value', 'name');
    }
}
