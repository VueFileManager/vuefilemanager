<?php

use App\Http\Controllers\App\MaintenanceController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/down', [MaintenanceController::class, 'down']);
    Route::get('/up', [MaintenanceController::class, 'up']);

    Route::group(['prefix' => 'upgrade'], function () {
        Route::get('/translations', [MaintenanceController::class, 'upgrade_translations']);
        Route::get('/database', [MaintenanceController::class, 'upgrade_database']);
    });
});
