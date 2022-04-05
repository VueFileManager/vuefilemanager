<?php

use Domain\Maintenance\Controllers\MaintenanceModeController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/down', [MaintenanceModeController::class, 'down']);
    Route::get('/up', [MaintenanceModeController::class, 'up']);
});
