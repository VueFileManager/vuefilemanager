<?php
namespace Domain\Settings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Domain\Settings\Models\Setting;
use App\Http\Controllers\Controller;

class AdminSettingsController extends Controller
{
    /**
     * Get table content
     */
    public function __invoke(Request $request): Collection
    {
        if (str_contains($request->get('column'), '|')) {
            $columns = explode('|', $request->get('column'));

            return Setting::whereIn('name', $columns)
                ->pluck('value', 'name');
        }

        return Setting::where('name', $request->get('column'))
            ->pluck('value', 'name');
    }
}
