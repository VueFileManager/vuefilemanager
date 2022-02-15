<?php
namespace Domain\Homepage\Controllers;

use DB;
use PDOException;
use Domain\Pages\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Support\Status\Actions\GetServerSetupStatusAction;

class IndexController
{
    public function __construct(
        public GetServerSetupStatusAction $getServerSetupStatus,
    ) {
    }

    /**
     * Show index page
     */
    public function __invoke(): Application|Factory|View
    {
        $setup_status = 'installation-needed';

        try {
            // Try to connect to database
            DB::getPdo();

            // Get setup status
            $setup_status = get_setup_status();

            // Get app pages
            $pages = Page::all();

            // Get all settings
            $settings = get_settings_in_json();
        } catch (PDOException $e) {
        }

        if ($setup_status === 'installation-needed') {
            $status_check = ($this->getServerSetupStatus)();
        }

        return view('index')
            ->with('status_check', $status_check ?? [])
            ->with('settings', $settings ?? null)
            ->with('legal', $pages ?? null)
            ->with('installation', $setup_status);
    }
}
