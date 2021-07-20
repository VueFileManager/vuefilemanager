<?php

use Domain\Maintenance\Controllers\MaintenanceModeController;
use Domain\Maintenance\Controllers\UpgradeDatabaseController;
use Domain\Maintenance\Controllers\UpgradeTranslationsController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/down', [MaintenanceModeController::class, 'down']);
    Route::get('/up', [MaintenanceModeController::class, 'up']);

    Route::group(['prefix' => 'upgrade'], function () {
        Route::get('/translations', UpgradeTranslationsController::class);
        Route::get('/database', UpgradeDatabaseController::class);
    });
});
