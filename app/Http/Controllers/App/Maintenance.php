<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\LanguageTranslation;
use App\Services\LanguageService;
use Artisan;
use DB;
use Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Schema;

class Maintenance extends Controller
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __construct()
    {
        // Check admin permission
        Gate::authorize('maintenance');
    }

    /**
     *  Start maintenance mode
     */
    public function up()
    {
        $command = Artisan::call('up');

        if ($command === 0) {
            echo 'System is in production mode';
        }
    }

    /**
     *  End maintenance mode
     */
    public function down()
    {
        $command = Artisan::call('down');

        if ($command === 0) {
            echo 'System is in maintenance mode';
        }
    }

    /**
     * Get new language translations from default translations
     * and insert it into database
     *
     * @return Application|ResponseFactory|Response
     */
    public function upgrade_translations()
    {
        resolve(LanguageService::class)
            ->upgrade_language_translations();

        return response('Done.', 201);
    }

    /**
     * @return int|mixed
     */
    public function upgrade_database()
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
