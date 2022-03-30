<?php

use Domain\Maintenance\Controllers\UpgradeTranslationsController;
use Domain\Maintenance\Controllers\MaintenanceModeController;
use Domain\Maintenance\Controllers\UpgradeSystemController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/down', [MaintenanceModeController::class, 'down']);
    Route::get('/up', [MaintenanceModeController::class, 'up']);

    Route::group(['prefix' => 'upgrade'], function () {
        Route::get('/translations', UpgradeTranslationsController::class);
        Route::get('/system', UpgradeSystemController::class);
    });
});
