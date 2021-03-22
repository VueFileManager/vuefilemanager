<?php

use App\Http\Controllers\Oasis\AdminController;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'admin'], function () {

    Route::get('/company-details', [AdminController::class, 'get_company_details']);

    // Users
    Route::post('/users/create', [AdminController::class, 'register_new_client']);
});
