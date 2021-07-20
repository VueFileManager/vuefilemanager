<?php

use App\Users\Controllers\AuthController;
use App\Users\Controllers\AccountController;
use Domain\Payments\Controllers\PaymentMethodsController;
use Domain\Subscriptions\Controllers\GetSetupIntentController;
use Domain\Subscriptions\Controllers\SubscriptionCancelController;
use Domain\Subscriptions\Controllers\SubscriptionController;
use Domain\Subscriptions\Controllers\SubscriptionResumeController;
use Domain\Subscriptions\Controllers\SubscriptionUpgradeController;

Route::post('/check', [AuthController::class, 'check_account']);

// Email verification
Route::get('/email/verify/{id}', [AccountController::class, 'email_verification'])->name('verification.verify');
Route::post('/email/resend/verify', [AccountController::class, 'resend_verification_email'])->name('verification.send');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Account
    Route::patch('/relationships/settings', [AccountController::class, 'update_user_settings']);
    Route::delete('/token/revoke/{token}', [AccountController::class, 'revoke_token']);
    Route::post('/token/create', [AccountController::class, 'create_token']);
    Route::post('/password', [AccountController::class, 'change_password']);
    Route::get('/subscription', [SubscriptionController::class, 'show']);
    Route::get('/tokens', [AccountController::class, 'tokens']);
    Route::get('/invoices', [AccountController::class, 'invoices']);
    Route::get('/storage', [AccountController::class, 'storage']);
    Route::get('/', [AccountController::class, 'user']);

    // Payment cards
    Route::apiResource('/payment-cards', PaymentMethodsController::class);

    // Subscription
    Route::group(['prefix' => 'subscription'], function () {
        Route::get('/setup-intent', GetSetupIntentController::class);
        Route::post('/upgrade', SubscriptionUpgradeController::class);
        Route::post('/cancel', SubscriptionCancelController::class);
        Route::post('/resume', SubscriptionResumeController::class);
    });
});
