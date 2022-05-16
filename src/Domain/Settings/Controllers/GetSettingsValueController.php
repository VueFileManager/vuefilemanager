<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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
    ): JsonResponse {
        if (str_contains($request->get('column'), '|')) {
            $columns = collect(explode('|', $request->get('column')))
                ->each(function ($column) {
                    if (in_array($column, $this->blacklist)) {
                        abort(
                            response()->json(accessDeniedError(), 401)
                        );
                    }
                });

            $settings = Setting::whereIn('name', $columns)
                ->pluck('value', 'name');

            return response()->json($settings);
        }

        if (in_array($request->get('column'), $this->blacklist)) {
            abort(
                response()->json(accessDeniedError(), 401)
            );
        }

        $settings = Setting::where('name', $request->get('column'))
            ->pluck('value', 'name');

        return response()->json($settings);
    }
}
