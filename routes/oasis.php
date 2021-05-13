<?php

use App\Http\Controllers\Oasis\AdminController;
use App\Http\Controllers\Oasis\SubscriptionController;

Route::group(['middleware' => 'api', 'prefix' => '/api/oasis'], function () {
    // Admin
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'admin'], function () {
        Route::get('/company-details', [AdminController::class, 'get_company_details']);
        Route::post('/users/create-order', [AdminController::class, 'create_order']);
        Route::post('/users/create-user', [AdminController::class, 'create_user']);
    });

    // Subscription
    Route::group(['prefix' => 'subscribe'], function () {
        Route::post('/{order?}', [SubscriptionController::class, 'subscribe']);
        Route::get('/{order}', [SubscriptionController::class, 'get_subscription_request']);
        Route::get('/{order}/setup-intent', [SubscriptionController::class, 'get_setup_intent']);
    });
});

// Web routes
Route::group(['middleware' => 'web', 'prefix' => 'oasis'], function () {
    Route::post('/subscribe/{order}/set-password', [SubscriptionController::class, 'set_password']);
});
