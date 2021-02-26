<?php

use App\Http\Controllers\General\Maintenance;

Route::post('/upgrade', [Maintenance::class, 'upgrade']);
Route::get('/down', [Maintenance::class, 'down']);
Route::get('/up', [Maintenance::class, 'up']);