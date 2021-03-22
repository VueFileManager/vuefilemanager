<?php

use App\Http\Controllers\Oasis\AdminController;

Route::group(['prefix' => 'admin'], function () {

    Route::get('/company-details', [AdminController::class, 'get_company_details']);
});
