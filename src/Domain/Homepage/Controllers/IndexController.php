<?php
namespace Domain\Homepage\Controllers;

use Illuminate\View\View;
use Domain\Pages\Models\Page;
use Doctrine\DBAL\Driver\PDOException;

class IndexController
{
    /**
     * Show index page
     */
    public function __invoke(): View
    {
        try {
            // Try to connect to database
            \DB::getPdo();

            // Get setup status
            $setup_status = get_setup_status();

            // Get app pages
            $pages = Page::all();

            // Get all settings
            $settings = get_settings_in_json();
        } catch (PDOException $e) {
            $setup_status = 'setup-database';
        }

        return view('index')
            ->with('settings', $settings ?? null)
            ->with('legal', $pages ?? null)
            ->with('installation', $setup_status);
    }
}
