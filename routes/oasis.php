<?php

use App\Http\Controllers\Oasis\AdminController;
use App\Http\Controllers\Oasis\SubscriptionController;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'admin'], function () {

    Route::get('/company-details', [AdminController::class, 'get_company_details']);
    Route::post('/users/create', [AdminController::class, 'register_new_client']);
});

Route::get('/subscription-request/{order}', [SubscriptionController::class, 'get_subscription_request']);
Route::get('/subscribe/{order}/setup-intent', [SubscriptionController::class, 'get_setup_intent']);
Route::post('/subscribe/{order}/set-password', [SubscriptionController::class, 'set_password']);
Route::post('/subscribe/{order}', [SubscriptionController::class, 'subscribe']);
