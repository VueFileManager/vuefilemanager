<?php

use App\Http\Controllers\App\Maintenance;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/down', [Maintenance::class, 'down']);
    Route::get('/up', [Maintenance::class, 'up']);

    Route::group(['prefix' => 'upgrade'], function () {
        Route::get('/translations', [Maintenance::class, 'upgrade_translations']);
        Route::get('/database', [Maintenance::class, 'upgrade_database']);
    });
});
