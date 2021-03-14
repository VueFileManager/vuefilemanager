<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\PaymentMethodsController;
use App\Http\Controllers\User\SubscriptionController;

Route::post('/check', [AuthController::class, 'check_account']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    // Account
    Route::patch('/relationships/settings', [AccountController::class, 'update_user_settings']);
    Route::post('/password', [AccountController::class, 'change_password']);
    Route::get('/subscription', [SubscriptionController::class, 'show']);
    Route::get('/invoices', [AccountController::class, 'invoices']);
    Route::get('/storage', [AccountController::class, 'storage']);
    Route::get('/', [AccountController::class, 'user']);

    // Payment cards
    // TODO: testy
    Route::delete('/payment-cards/{id}', [PaymentMethodsController::class, 'delete']);
    Route::patch('/payment-cards/{id}', [PaymentMethodsController::class, 'update']);
    Route::post('/payment-cards', [PaymentMethodsController::class, 'store']);
    Route::get('/payments', [PaymentMethodsController::class, 'index']);

    // Subscription
    Route::group(['prefix' => 'subscription'], function () {
        Route::get('/setup-intent', [SubscriptionController::class, 'setup_intent']);
        Route::post('/upgrade', [SubscriptionController::class, 'upgrade']);
        Route::post('/cancel', [SubscriptionController::class, 'cancel']);
        Route::post('/resume', [SubscriptionController::class, 'resume']);
    });
});

