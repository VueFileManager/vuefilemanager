<?php

use Support\Scheduler\Actions\ReportUsageAction;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('report:usage', fn () => resolve(ReportUsageAction::class)())
    ->describe('Store user usage estimates for metered billing');
