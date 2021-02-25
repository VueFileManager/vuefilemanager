<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Page;
use App\Setting;
use Artisan;
use Illuminate\Http\Request;
use Schema;

class UpgradeAppController extends Controller
{

    /**
     *  Start maintenance mode
     */
    public function up() {
        $command = Artisan::call('up');

        if ($command === 0) {
            echo 'System is in production mode';
        }
    }

    /**
     *  End maintenance mode
     */
    public function down() {
        $command = Artisan::call('down');

        if ($command === 0) {
            echo 'System is in maintenance mode';
        }
    }

    /**
     *  Upgrade database
     */
    public function upgrade()
    {
        $this->upgrade_database();
    }

    /**
     * @return int|mixed
     */
    private function upgrade_database()
    {
        $command = Artisan::call('migrate', [
            '--force' => true
        ]);

        if ($command === 0) {
            echo 'Operation was successful.';
        }

        if ($command === 1) {
            echo 'Operation failed.';
        }
        return $command;
    }
}
